<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Empleo Gijon</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/acceso.css') }}">
</head>

<body class="cuerpo-acceso">
    <main class="contenedor-principal-login">
        <article class="tarjeta-acceso">
            <header class="cabecera-login">
                <img src="{{ asset('assets/imagenes/logo_portal_empleo.png') }}" alt="Logo Portal empleo" class="logo-acceso">
                <h1>Iniciar Sesión</h1>
                <p>Accede a tu panel de gestión de empleo</p>
            </header>

            @if(session('error'))
            <div class="alerta alerta-error" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif
            
            @if (session('mensaje'))
            <div class="alerta alerta-exito">
                <p><i class="fa-solid fa-circle-check"></i> {{ session('mensaje') }}</p>
            </div>
            @endif
            <form action="{{ route('autenticar') }}" method="POST" class="formulario-estandar">
                @csrf

                <div class="grupo-input">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-con-icono">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="input-estandar" placeholder="correo@ejemplo.com">
                    </div>
                </div>

                <div class="grupo-input">
                    <div class="etiqueta-con-enlace">
                        <label for="password">Contraseña</label>
                        <a href="{{ route('password.request') }}" class="enlace-olvido">¿Has olvidado tu contraseña?</a>
                    </div>
                    <div class="input-con-icono">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" required minlength="8" class="input-estandar" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="boton-acceso">Entrar al Portal</button>
            </form>

            <footer class="pie-tarjeta-acceso">
                <p>¿Aún no tienes cuenta? <a href="{{ route('register') }}" class="enlace-secundario">Regístrate aquí</a></p>
            </footer>
        </article>
    </main>
</body>

</html>