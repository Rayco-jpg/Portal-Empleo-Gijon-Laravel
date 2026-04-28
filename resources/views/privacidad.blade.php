@extends('layouts.app')

@section('content')
<div class="seccion-legal-layout">
    <div class="tarjeta-legal-blanca">
        <header>
            <i class="fa-solid fa-user-shield"></i>
            <h1>Privacidad</h1>
        </header>

        <div class="contenido">
            <p>De acuerdo con el Reglamento General de Protección de Datos (RGPD), te informamos sobre cómo tratamos tu información personal.</p>
            
            <h2><i class="fa-solid fa-database"></i> ¿Qué datos recogemos?</h2>
            <p>Solo almacenamos los datos necesarios para tu perfil: Nombre, apellidos, correo electrónico y la información que decidas incluir en tu Currículum Vitae.</p>

            <h2><i class="fa-solid fa-bullseye"></i> Finalidad</h2>
            <p>Tus datos se utilizan exclusivamente para permitir que las empresas de Gijón puedan valorar tu perfil cuando te inscribes en una oferta de trabajo.</p>

            <h2><i class="fa-solid fa-scale-balanced"></i> Tus Derechos</h2>
            <ul>
                <li><strong>Acceso y Rectificación:</strong> Puedes cambiar tus datos en cualquier momento desde tu perfil.</li>
                <li><strong>Eliminación:</strong> Tienes derecho a darte de baja y borrar toda tu información de nuestra base de datos permanentemente.</li>
                <li><strong>Seguridad:</strong> No compartimos tus datos con terceros ajenos al proceso de selección.</li>
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