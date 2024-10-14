@include('header')
<div class="container content">
    <h1 class="my-2 columns is-mobile is-centered">¿Cómo deseas acceder?</h1>
    <section class="my-2 columns is-mobile is-centered">
        <a href="{{route('login-google')}}">Accede con Google</a>
    </section>
    
    <hr/>
    <h1 class="my-2 columns is-mobile is-centered">o</h1>

    <section class="my-2 columns is-mobile is-centered">
        <form method="POST" action="{{ route('login-account-email') }}">
            @csrf      
            <div class="field">
                <div class="control has-icons-left">
                    <input required class="input" type="email" name="email" {{old('email')}} placeholder="Tu correo electrónico">
                    <span class="icon is-small is-left">
                        <i class="fas fa-envelope"></i>
                    </span>
                </div>
            </div>
            <div class="field">
                <div class="control has-icons-left">
                    <input required class="input" type="password" name="password" placeholder="Escribe una contraseña">
                    <span class="icon is-small is-left">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
            </div>
            <div class="field">
                <div class="control has-icons-left">
                    <button class="button is-success is-fullwidth">Iniciar sesión</button>
                </div>
            </div>
        </form>
    </section>
    <section class="my-2 columns is-mobile is-centered">
        ¿No tienes una cuenta? <a href="{{route('register')}}">| Crear cuenta</a>
    </section>
    <hr>

</div>

@include('footer')