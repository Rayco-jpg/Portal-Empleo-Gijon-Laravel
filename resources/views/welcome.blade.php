<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gijón Empleo - Bienvenidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="cuerpo-acceso">

    <main class="tarjeta-acceso">
        <header class="cabecera-landing">
            <img src="{{ asset('assets/imagenes/logo_portal_empleo.png') }}" alt="Logo Gijón Empleo" class="logo-acceso">
            <h1 class="titulo-principal">Gijón<span>Empleo</span></h1>
        </header>

        <section class="contenido-bienvenida">
            <h2 class="subtitulo-landing">Tu futuro profesional comienza aquí</h2>
            <p class="descripcion-landing">Conectamos el talento de nuestra ciudad con las mejores empresas de Gijón.</p>
            
            <nav class="acciones-acceso">
                <a href="{{ route('login') }}" class="boton-acceso">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="enlace-secundario">¿No tienes cuenta? Regístrate gratis</a>
            </nav>
        </section>

        <footer class="pie-tarjeta-acceso">
            <p>&copy; 2026 Gijón Empleo - Proyecto Final de Grado</p>
        </footer>
    </main>

</body>
</html>