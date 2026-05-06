@extends('layouts.app')
@section('title', 'Visitas de Empresas')
@section('content')
<div id="seccion-visitas">
    <div class="contenedor-visitas">
        <div class="tarjeta-visitas">
            <div class="cabecera-flex">
                <div>
                    <h1 class="titulo-pagina">Empresas que te han visitado</h1>
                    <span class="subtitulo-pagina">Descubre qué empresas están interesadas en tu perfil</span>
                </div>
                <a href="{{ route('buscador') }}" class="boton-azul-volver">
                    <i class="fas fa-arrow-left"></i> Volver al buscador
                </a>
            </div>

            <div class="tabla-responsiva">
                <table class="tabla-visitas">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Fecha de visita</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visitas as $visita)
                            <tr>
                                <td data-label="Empresa">
                                    <span class="nombre-empresa-texto">{{ $visita->nombre_empresa }}</span>
                                </td>
                                <td data-label="Fecha de visita">
                                    <div class="fecha-wrapper">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ \Carbon\Carbon::parse($visita->fecha_visita)->diffForHumans() }}
                                    </div>
                                </td>
                                <td data-label="Acción">
                                    <a href="{{ route('perfil.empresa.publico', $visita->id_usuario) }}" class="btn-ver">
                                        Ver empresa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="tabla-vacia">
                                    Aun no tienes visitas de empresas. ¡Sigue mejorando tu perfil!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection