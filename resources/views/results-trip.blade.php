@include('header')
@include('hero')
<div class="container py-6">
 
<div class="card px-2 my-5 columns">
    <form method="post" action="" class="field is-grouped">
     <!--token formulario-->   
        @csrf
        <div class="control">
            <span class="icon">
                <i class="fa-solid fa-location"></i>
            </span>
            <select style="width:200px" name="origen" id="origen">

            </select>
        </div>
        
        <div class="control">
            <span class="icon">
                <i class="fa-solid fa-location"></i>
            </span>
            <select style="width:200px" name="destino" id="destino">

            </select>
        </div>
        <div class="control" title="fecha de salida">
            <input class="input is-success" type="date" name="fecha" id="fecha" min="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
        </div>

        <div class="field" title="numero de asientos">
            <p class="control has-icons-left">
            
            <span class="icon">
                <i class="fa-solid fa-chair"></i>
            </span>
            <input class="input is-success" type="number" name="asientos" id="asientos" min="1" max="4" value="1">
            </p>
            
        </div>

        <div class="control" title="numero de asientos">
            <button class="button is-success">Buscar</button>
            
        </div>

    </form>
</div>
<div class="card px-2 my-5">
@php
    $totalTrips= count($trips);
@endphp
</div>


    <article class="message column {{($totalTrips==0)?'is-warning':'is-success'}}">
        <div class="message-header">
            <p>{{$from}} -> {{$to}} | {{$totalTrips}} viajes disponibles | Salida: {{($date==date('Y-m-d')?'Hoy':'')}}{{$date}}</p>
        </div>
        <div class="message-body columns is-mobile is-centered"> 
        <span class="content">
        @if($totalTrips==0)
        <div class="content">
            <div class="columns">
                <div class="py-2 card px-2">
                    <div class="icon-text">
                        <span class="icon has-text-success">
                            <i class="fa-solid fa-chair"></i>
                        </span>
                        <span>Disponibles</span>

                        <span class="icon has-text-danger">
                            <i class="fa-solid fa-chair"></i>
                        </span>
                        <span>Ocupados</span>

                        <span class="icon has-text-info">
                            <i class="fa-solid fa-chair"></i>
                        </span>
                        <span>libres</span>
                    </div>
                </div>
            </div>
            <h2>Aún no hay viajes disponibles para esta fecha</h2>
            <p>{{$from}} -> {{$to}}</p>
            <p>Intenta cambiar las fechas</p>
        </div>
        </div>
        @else
        <div class="content">
            @foreach ($trips as $info )
            <div class="card my-2">
                <div class="card-content">
                    <div class="media">
                    <div class="media-left">
                        <figure class="image is-48x48">
                        <img
                            src="https://bulma.io/assets/images/placeholders/96x96.png"
                            alt="Placeholder image"
                        />
                        </figure>
                    </div>
                    <div class="media-content columns">
                        <div class="column">
                        <p class="title is-4">{{ $info->driver->name }}</p>
                        <span>Calificación:</span>
                        <p class="subtitle is-6">
                            <span class="icon has-text-warning">
                                <i class="fa-solid fa-star"></i>
                            </span>
                            {{ $info->driver->rating }}
                            <br>
                        </p>    
                        </div> 

                        <div class="column">                
                            <p class="subtitle is-6">                          
                                <br>
                                <span class="icon has-text-success">
                                    <i class="fa-solid fa-location"></i>
                                </span>
                                {{ $from }} ->
                                <span class="icon has-text-success">
                                    <i class="fa-solid fa-location"></i>
                                </span>
                                {{ $to }}
                            </p> 
                            
                            <p class="subtitle is-6">                          
                                <br>
                                <span class="icon has-text-success">
                                    <i class="fa-solid fa-clock"></i>
                                </span>
<!--0,5 me sirve para solo mostrar horas y minutos en el relooh-->
                                {{ substr($info->departure_time,0,5) }} ->
<!--calculo de hora de llegada-->                                
                                @php
                                    $horaOriginal = new DateTime($info->departure_time);
                                    $duracion = new  DateInterval("PT".intval(substr($info->trip_duration,0,2))."H".intval(substr($info->trip_duration,3,2))."M");

                                    $horaLlegada = $horaOriginal->add($duracion);
                                    @endphp
                                {{substr($horaLlegada->format('H:i:s').PHP_EOL,0,5)}}
                                ( {{intval(substr($info->trip_duration,0,2))."h ".intval(substr($info->trip_duration,3,2))."m"}})
                            </p>
                            <p style="font-size: 16pt;">                          
                                <br>
                                <span class="icon has-text-success">
                                    <i class="fa-solid fa-dollar"></i>
                                </span>
                                ${{ number_format($info->price_per_seat,0,',','.') }}
                                p/p                       
                            </p> 
                            <p class="subtitle is-6">                          
                                <div class="py-2 card px-2">
                                    <div class="icon-text">
                                        @for ($inicio=0; $inicio < $info->available_seats - $info->occupied_seats;$inicio++)
                                            <span class="icon has-text-success" title="Disponible">
                                                <i class="fa-solid fa-chair"></i>
                                            </span>                                       
                                        @endfor
                                        @for ($inicio=0; $inicio < $info->behind_available_seats;$inicio++)
                                            <span class="icon has-text-info" title="Libre">
                                                <i class="fa-solid fa-chair"></i>
                                            </span>                                      
                                        @endfor
                                        @for ($inicio=0; $inicio < $info->occupied_seats;$inicio++)
                                            <span class="icon has-text-danger" title="Ocupado">
                                                <i class="fa-solid fa-chair"></i>
                                            </span>                                      
                                        @endfor 
                                        | {{($info-> available_seats - $info->occupied_seats)}} Asientos disponibles                                                                                                                              
                                    </div>
                                </div>
                            </p> 
                        </div>
                    </div>
                    </div>

                    <div class="content columns">
                        <div class="column">
                            Parque jurásico la mejor película. <a>@bulmaio</a>. <a href="#">#css</a>
                            <a href="#">#responsive</a>
                            <br />
                            <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                        </div>
                        <div class="column">
                            Parque jurásico la mejor película. <a>@bulmaio</a>. <a href="#">#css</a>
                            <a href="#">#responsive</a>
                            <br />
                            <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <button class="button is-success is-fullwidth">separar</button>
                    
                    </footer>
                </div>
                </div>
            @endforeach
        </div>
        @endif
        </span> 
    </article>
      
@include('footer')
