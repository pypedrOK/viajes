<?php

use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('home');

Route::post('searchtrip',[TripController::class,'searchTrip'])->name('searchTrip');
//con get aunque el usuario actualice pagina la info queda guardada, verified es para filtrar cuentas creadas
Route::get('search/{from}/{to}/{date}/{seats}/{sort}/{verified}',[TripController::class,'search']);
