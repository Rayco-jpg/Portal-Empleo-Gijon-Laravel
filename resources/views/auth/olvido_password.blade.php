<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Portal Empleo Gijón</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/acceso.css') }}">
</head>
<body class="cuerpo-acceso">
    <main class="contenedor-principal-login">
        <div class="tarjeta-acceso">
            <header class="cabecera-login">
                <img src="{{ asset('assets/imagenes/logo_portal_empleo.png') }}" alt="Logo Portal Empleo" class="logo-acceso">
                <h1>¿Olvidaste tu clave?</h1>
                <p>Introduce tu correo y te enviaremos las instrucciones.</p>
            </header>

            {{-- Mensaje de éxito de Laravel --}}
            @if (session('enviado'))
                <div class="alerta alerta-exito">
                    <p><i class="fa-solid fa-circle-check"></i> {{ session('enviado') }}</p>
                </div>
            @endif

            <form action="{{ route('password.enviar') }}" method="POST" class="formulario-estandar">
                @csrf
                <div class="grupo-input">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-con-icono">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" required class="input-estandar" placeholder="ejemplo@correo.com">
                    </div>
                </div>

                <button type="submit" class="boton-acceso">Enviar enlace</button>
            </form>

            <footer class="pie-tarjeta-acceso">
                <a href="{{ route('login') }}" class="enlace-secundario">
                    <i class="fa-solid fa-arrow-left"></i> Volver al inicio de sesión
                </a>
            </footer>
        </div>
    </main>
</body>
</html>