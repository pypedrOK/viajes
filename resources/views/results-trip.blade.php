@include('header')
@include('hero')
<div class="container py-6">
 
<div class="card px-2 my-5 columns">
    <form method="post" action="{{route('searchTrip')}}" class="field is-grouped">
     <!--token formulario-->   
        @csrf
        <input type="hidden" name="form2" value="true">
        <input type="hidden" name="verified" value="-1">
        <input type="hidden" name="sort" value="departure_time">
        <div class="control">
            <span class="icon">
                <i class="fa-solid fa-location"></i>
            </span>
            <input value="{{ $from }}" class="input" style="width:200px" name="origen" id="myInputFrom"/>
        </div>
        
        <div class="control">
            <span class="icon">
                <i class="fa-solid fa-location"></i>
            </span>
            <input value="{{ $to }}" class="input" style="width:200px" name="destino" id="myInputTo"/>
        </div>
        <div ccontrol" title="fecha de salida">
            <input class="input is-success" type="date" name="fecha" id="fecha" min="{{date('Y-m-d')}}" value="{{ $date }}">
        </div>

        <div class="field" title="numero de asientos">
            <p class="control has-icons-left">
            
            <span class="icon">
                <i class="fa-solid fa-chair"></i>
            </span>
            <input class="input is-success" type="number" name="asientos" id="asientos" min="1" max="4" value={{ $seats }}>
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
                        <img style="border-radius: 25px;"
                            src="{{ optional($info->driver)->photo ? $info->driver->photo : asset('img/auto.png')}}" alt="Placeholder image"
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
                                         {{($info-> available_seats - $info->occupied_seats)}} Disponibles                                                                                                                              
                                    </div>
                                </div>
                            </p> 
                        </div>
                    </div>
                    </div>

                                    

                    <div class="content">
                        <p style="color:gray">
                            Aqui encontraras mas información detallada del viaje y datos que te ayudarán a elegir el que mas se ajuste a tus necesidades
                        </p>
                        <p>
                            <strong>Comentarios del conductor: </strong>{{$info->details}}            
                        </p>
                    </div>
                    <footer class="card-footer">
<!--aqui se muestra en detalles horas de viajes y duracion-->
                        <button onclick="showDetails('{{ $info->id }}','{{ $info->departure_date }}','{{ substr($info->departure_time,0,5) }} -> {{substr($horaLlegada->format('H:i:s').PHP_EOL,0,5)}} ( {{intval(substr($info->trip_duration,0,2)).'h '.intval(substr($info->trip_duration,3,2)).'m'}})','{{ $info->available_seats - $info->occupied_seats }}','{{ $info->occupied_seats?$info->occupied_seats: 0 }}','{{ $info->pets_allowed?'SI':'NO' }}','{{ $info->smoking_allowed?'SI':'NO'}}','{{ $info->pickup_point }}','{{ $info->dropoff_point }}','{{ $info->details }}','{{ optional($info->driver)->photo ? $info->driver->photo : asset('img/auto.png')}}')" data-micromodal-trigger="modal-details" style="border-radius: 50px;" class="button is-success is-fullwidth">Detalles</button>
                    
                    </footer>
                </div>
                </div>
            @endforeach
        </div>
        @endif
        </span> 
    </article>
</div>


@include('trip-details')



<!--Script de autocompletado-->
<script>
  function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
                  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function (e) {
    var a,
      b,
      i,
      val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) {
      return false;
    }
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items card");
    /*append the DIV element as a child of the autocomplete container:*/
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i < arr.length; i++) {
      /*check if the item starts with the same letters as the text field value:*/
      if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
        b.innerHTML += arr[i].substr(val.length);
        /*insert a input field that will hold the current array item's value:*/
        b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function (e) {
          /*insert the value for the autocomplete text field:*/
          inp.value = this.getElementsByTagName("input")[0].value;
          if (document.getElementById("myInputFrom") == inp) {
            getDestinationsAjax($("#myInputFrom").val());
            $("#myInputTo").val("");
            $("#myInputTo").focus();
          }
          /*close the list of autocompleted values,
                              (or any other open lists of autocompleted values:*/
          closeAllLists();
        });
        a.appendChild(b);
      }
    }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function (e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
      /*If the arrow DOWN key is pressed,
                        increase the currentFocus variable:*/
      currentFocus++;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 38) {
      //up
      /*If the arrow UP key is pressed,
                        decrease the currentFocus variable:*/
      currentFocus--;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 13) {
      /*If the ENTER key is pressed, prevent the form from being submitted,*/
      e.preventDefault();
      if (currentFocus > -1) {
        /*and simulate a click on the "active" item:*/
        if (x) x[currentFocus].click();
      }
    }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = x.length - 1;
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
                    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
    closeAllLists(e.target);
  });
}
$(document).ready(function () {
  var cities = ["Caburgua", "Villarrica"];
  $.ajax({
    url: "{{ route('city.index') }}?name=true",
    type: "GET",
    dataType: "json",
    success: function (respuesta) {
      cities = respuesta.results;
      autocomplete(document.getElementById("myInputFrom"), cities);
      $.ajax({
        url: `/api/ajax/getDestinationsAjax/{{ $from }}`,
        type: "GET",
        dataType: "json",
        success: function (respuesta) {
          cities = respuesta.results;
          autocomplete(document.getElementById("myInputTo"), cities);
        },
        error: function (err) {
          console.error("error", err);
        }
      });
    },
    error: function (err) {
      console.error("error", err);
    }
  });
});

function getDestinationsAjax(texto) {
  $.ajax({
    url: `/api/ajax/getDestinationsAjax/${texto}`,
    type: "GET",
    dataType: "json",
    success: function (respuesta) {
      cities = respuesta.results;
      autocomplete(document.getElementById("myInputTo"), cities);
    },
    error: function (err) {
      console.error("error", err);
    }
  });
}

   
</script>    
      
@include('footer')
