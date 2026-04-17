<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña - Portal Empleo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/acceso.css') }}">
</head>
<body class="cuerpo-acceso">
    <main class="contenedor-principal-login">
        <div class="tarjeta-acceso">
            <header class="cabecera-login">
                <img src="{{ asset('assets/imagenes/logo_portal_empleo.png') }}" alt="Logo" class="logo-acceso">
                <h1>Nueva Contraseña</h1>
                <p>Introduce tu nueva clave de acceso</p>
            </header>
            @if($errors->any())
                <div class="alerta alerta-error">
                    @foreach ($errors->all() as $error)
                        <p><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</p>
                    @endforeach
                    <a href="{{ route('password.request') }}" class="enlace-reintento">Solicitar nuevo enlace</a>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="formulario-estandar">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="grupo-input">
                    <label for="password">Nueva Contraseña</label>
                    <div class="input-con-icono">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" required class="input-estandar" placeholder="Mínimo 8 caracteres">
                    </div>
                </div>

                <div class="grupo-input">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <div class="input-con-icono">
                        <i class="fa-solid fa-check-double"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="input-estandar" placeholder="Repite tu contraseña">
                    </div>
                </div>

                <button type="submit" class="boton-acceso">Actualizar Contraseña</button>
            </form>
            
            <footer class="pie-tarjeta-acceso">
                <a href="{{ route('login') }}" class="enlace-secundario">Cancelar y volver</a>
            </footer>
        </div>
    </main>
</body>
</html>