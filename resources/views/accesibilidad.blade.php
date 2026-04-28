@extends('layouts.app')

@section('content')
<div class="seccion-legal-layout">
    <div class="tarjeta-legal-blanca">
        <header>
            <i class="fa-solid fa-universal-access"></i>
            <h1>Accesibilidad</h1>
        </header>

        <div class="contenido">
            <p>Nuestro objetivo es que el <strong>Portal de Empleo Gijón</strong> sea accesible para todos los ciudadanos, eliminando barreras visuales y técnicas.</p>
            
            <h2><i class="fa-solid fa-moon"></i> Salud Visual</h2>
            <p>Hemos implementado un <strong>Modo Oscuro</strong> nativo que reduce la fatiga visual y facilita la lectura a personas con sensibilidad a la luz o visión reducida.</p>

            <h2><i class="fa-solid fa-code"></i> Estándares Técnicos</h2>
            <ul>
                <li><strong>Estructura Semántica:</strong> Utilizamos etiquetas HTML correctas (Nav, Main, Footer) para que los lectores de pantalla naveguen fácilmente.</li>
                <li><strong>Contraste:</strong> Los colores han sido seleccionados para cumplir con los estándares mínimos de contraste de la W3C.</li>
                <li><strong>Navegación:</strong> El portal es totalmente responsive, adaptándose a cualquier tamaño de pantalla o zoom del navegador.</li>
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