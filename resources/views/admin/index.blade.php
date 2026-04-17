@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="panel-administracion">
    <header>
        <h1 class="titulo-pagina">Panel de Control</h1>
        <p class="subtitulo-pagina">Resumen del estado actual de la plataforma.</p>
    </header>
    
    <nav class="menu-navegacion">
        <a href="{{ route('admin.index') }}" class="enlace-menu activo">Inicio</a>
        <a href="{{ route('admin.usuarios') }}" class="enlace-menu">Gestión de Usuarios</a>
        <a href="{{ route('admin.ofertas') }}" class="enlace-menu">Gestión de Ofertas</a>
    </nav>

    <div class="contenedor-tarjetas-resumen">
        <div class="tarjeta-estadistica">
            <div class="icono-tarjeta color-usuarios">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="info-tarjeta">
                <span class="etiqueta-tarjeta">Usuarios Totales</span>
                <span class="valor-tarjeta">{{ $usuarios->count() }}</span>
            </div>
        </div>

        <div class="tarjeta-estadistica">
            <div class="icono-tarjeta color-ofertas">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <div class="info-tarjeta">
                <span class="etiqueta-tarjeta">Ofertas Activas</span>
                <span class="valor-tarjeta">{{ \App\Models\Oferta::count() }}</span>
            </div>
        </div>

        <div class="tarjeta-estadistica">
            <div class="icono-tarjeta color-empresas">
                <i class="fa-solid fa-building"></i>
            </div>
            <div class="info-tarjeta">
                <span class="etiqueta-tarjeta">Empresas registradas</span>
                <span class="valor-tarjeta">{{ $usuarios->where('tipo_usuario', 'empresa')->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection