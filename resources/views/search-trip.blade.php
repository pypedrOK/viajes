<div class="card px-4 py-4 columns is-mobile is-centered">
    <form method="post" action="{{route('searchTrip')}}" class="field is-grouped">
     <!--token formulario-->   
        @csrf
        <input type="hidden" name="verified" value="-1">
        <input type="hidden" name="sort" value="departure_time">
        <div class="control">
            <span class="icon">
                <i class="fa-solid fa-location"></i>
            </span>
            <select required style="width:200px" name="origen" id="origen">

            </select>
        </div>
        
        <div class="control">
            <span class="icon">
                <i class="fa-solid fa-location"></i>
            </span>
            <select required style="width:200px" name="destino" id="destino">

            </select>
        </div>
        <div class="control" title="fecha de salida">
            <input required class="input is-success" type="date" name="fecha" id="fecha" min="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
        </div>

        <div class="field" title="numero de asientos">
            <p class="control has-icons-left">
            
            <span class="icon">
                <i class="fa-solid fa-chair"></i>
            </span>
            <input required class="input is-success" type="number" name="asientos" id="asientos" min="1" max="4" value="1">
            </p>
            
        </div>

        <div class="control" title="numero de asientos">
            <button class="button is-success">Buscar</button>
            
        </div>

    </form>
</div>