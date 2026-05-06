@extends('layouts.error_layout')
@section('title', 'Error 405 - Acceso no permitido')
@section('content')
<div class="contenedor-error">
    <div class="contenido-error">
        <h1 class="codigo-error">405</h1> <h2 class="titulo-error">Acceso no permitido</h2>
        <p class="descripcion-error">
            Lo sentimos, la acción que intentas realizar no es válida para esta página.
        </p>
        <div class="acciones-error">
            <a href="{{ url()->previous() }}" class="btn-volver-atras">
                <i class="fa-solid fa-arrow-left"></i> Volver atrás
            </a>
        </div>
    </div>
</div>
@endsection