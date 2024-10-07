<nav class="navbar has-shadow">

    <div class="navbar-brand">
<!-- route home va a pagina principal-->
        <a href="{{route('home')}}" class="navbar-item">
            <img style="height:70px; max-height: 70px;" src="{{asset('img/trip.png')}}" alt="logo" class="py-2 px-2">
        </a>
    </div>

    <nav class="navbar-menu" id="nav-links">
        <div class="navbar-end">
            <a href="" class="navbar-item">
                <span class="icon">
                 <i class="fa-solid fa-plus"></i>
                </span>Publica un viaje
            </a>
            <a href="" class="navbar-item">
                <span class="icon">
                    <i class="fa-solid fa-lock"></i>
                </span>Iniciar sesión
            </a>
            <a href="" class="navbar-item">
                <span class="icon">
                    <i class="fa-solid fa-user"></i>
                </span>Create un cuenta
            </a>
        </div>        
    </nav>
</nav>

