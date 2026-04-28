@extends('layouts.app')

@section('content')
<div class="seccion-legal-layout">
    <div class="tarjeta-legal-blanca">
        <header>
            <i class="fa-solid fa-cookie-bite"></i>
            <h1>Política de Cookies</h1>
        </header>

        <div class="contenido">
            <p>En el <strong>Portal de Empleo Gijón</strong> utilizamos cookies para mejorar tu experiencia de navegación y garantizar la seguridad de tu cuenta.</p>
            
            <h2><i class="fa-solid fa-gears"></i> Cookies Técnicas</h2>
            <p>Son esenciales para que la web funcione. Permiten mantener tu sesión activa, proteger el envío de formularios (Token CSRF) y recordar si has aceptado este aviso.</p>

            <h2><i class="fa-solid fa-palette"></i> Cookies de Personalización</h2>
            <p>Utilizamos una cookie local para recordar tu preferencia de <strong>Modo Oscuro</strong> o <strong>Modo Claro</strong>, de modo que se aplique automáticamente cada vez que nos visites.</p>

            <ul>
                <li><strong>Duración:</strong> La mayoría de nuestras cookies se borran al cerrar el navegador.</li>
                <li><strong>Gestión:</strong> Puedes bloquearlas desde la configuración de tu navegador, pero algunas funciones dejarán de funcionar.</li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/') }}" class="btn-legal-volver">
                <i class="fa-solid fa-house"></i> Volver Atras
            </a>
        </div>
    </div>
</div>
@endsection