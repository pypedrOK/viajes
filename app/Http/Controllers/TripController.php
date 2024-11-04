<?php
//en este archivo aparece resultados de busqueda
namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Reservation;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    //aqui se retorna la vista
    public function searchTrip(Request $request)
    {
        //retornar info de los campos rellenados
        if($request->input('origen') && $request->input('destino') && $request->input('fecha') && $request->input('asientos')){
            if ($request->has('form2')){
                $origen = City::where('name', $request->input('origen'))-> first();
                $destino = City::where('name', $request->input('destino'))-> first();

            }else{
                $origen = City::where('id', $request->input('origen'))-> first();
                $destino = City::where('id', $request->input('destino'))-> first();
            }
            
            
            if($origen && $destino){
                return redirect("/search/{$origen->id}/{$destino->id}/{$request->input('fecha')}/{$request->input('asientos')}/{$request->input('sort')}/{$request->input('verified')}");
            }else
            {
                 return redirect()->intended('/')->withErrors('Debe indicar origen y destino');
            }
        }
    }

    //se requiere que conductor, ciudad de origen y destino esten disponibles o activas
    public function search($from,$to,$date,$seats,$sort ='departure_time',$verified=null){
         $trips = Trip::with(['departureCity','arrivalCity','driver'])
         ->select(['trips.*',DB::raw('(SELECT SUM(seats)FROM reservations WHERE trip_id = trips.id)AS occupied_seats')])
         -> whereHas('departureCity', function($query){
            $query->where('active',1);
         })
         -> whereHas('arrivalCity', function($query){
            $query->where('active',1);
         })
         -> whereHas('driver', function($query)use($verified){
            $query->where('active',1);
            if($verified == 1){
                $query->where('verified',1);
                $query->where('dni_front','=/','');
                $query->where('dni_back','=/','');
            }
         })
         ->where('departure_city_id', $from)
         ->where('arrival_city_id', $to)
         ->where('available_seats', '>=',$seats)
         ->where('departure_date', $date)
         ->where('active', 1)
         ->orderBy($sort,'asc')
         ->get();

         //consulta a partir de

         $cityFrom = City :: find($from);
         $cityTo = City :: find($to );
         if($cityFrom && $cityTo){
            $from = $cityFrom->name;
            $to = $cityTo->name;

            return view('results-trip')->with(compact( 'from','to','seats','date','trips','sort','verified'));
         }else{

            return redirect()->withErrors('No hay viajes disponibles para estas condiciones');

         }

    }
    
    public function store(Request $request){
        $trip = new Trip;
        $trip->departure_city_id = $request->input('departure_city_id');
        $trip->arrival_city_id = $request->input('arrival_city_id');
        $trip->available_seats = $request->input('available_seats');
        $trip->behind_available_seats = $request->input('behind_available_seats');
        $trip->car_plate = $request->input('car_plate');
        $trip->trip_duration = $request->input('trip_duration');
        $trip->driver_id = $request->input('driver_id');
        $trip->departure_date = $request->input('departure_date');
        $trip->departure_time = $request->input('departure_time');
        $trip->pickup_point = $request->input('pickup_point');
        $trip->dropoff_point = $request->input('dropoff_point');
        //str para que se guarde el valor sin puntos como un entero
        $trip->price_per_seat = str_replace(".", "", $request->input('price_per_seat'));
        $trip->smoking_allowed = $request->input('smoking_allowed');
        $trip->pets_allowed = $request->input('pets_allowed');
        $trip->car_brand = $request->input('car_brand');
        $trip->phone = $request->input('phone');
        $trip->automatic_reservation = $request->input('automatic_reservation');
        $trip->details = $request->input('details');
        $trip->car_color = $request->input('car_color');

        $trip->save();

        return response()->json([
            'message' => "Viaje creado con éxito",
            'icon' =>'success'

        ],201);

    }
    //funcion historial
    public function history(){
        $sessionUserId = Auth()->user()->id;
        //obtener los viajes donde ha conducido el usuario
        $trips = Trip::with(['departureCity','arrivalCity','driver'])
            ->orWhere('driver_id', $sessionUserId)
            ->orderBy('id','desc')
            //aparecen ultimos 10 viajes
            ->limit(10)
            ->get();

        $trips->map(function($trip){
            //viajes como conductor
            $trip->is_driver = true;
            return $trip;

        });
        $reservations = User::find($sessionUserId)->reservations;  
        
        $tripIds = $reservations->pluck('trip_id')->unique()->toArray();

        //viajes de pasajero
        $trips2 = Trip::with(['departureCity','arrivalCity','driver'])
            ->whereIn('id', $tripIds)
            ->orderBy('id','desc')
            //aparecen ultimos 10 viajes
            ->limit(10)
            ->get();
        
        $trips2->map(function($trip){
            //viajes como conductor
            $trip->is_driver = false;
            return $trip;
        });
        //union de id de conductor y pasajero
        $allTripIds = $trips->pluck('id')->concat($trips2->pluck('id'))->toArray();

        $reservationsForTrips = Reservation::whereIn('trip_id',$allTripIds)->get();

        $passengerCountPerTrip = $reservationsForTrips->groupBy('trip_id')->map->count();

        $trips->each(function($trip) use($passengerCountPerTrip){

            $trip->passenger_count = $passengerCountPerTrip->get($trip->id,0); 

        });

        //union de listas ordenadas

        $trips = $trips->concat($trips2)->sortByDesc('departure_date');

        $date = date('Y-m-d');

        return view('history', compact('trips','date'));
    }
}
