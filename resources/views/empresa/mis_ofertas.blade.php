@extends('layouts.app')

@section('content')
    <section class="seccion-panel">
        <header class="cabecera-panel">
            <h2 class="titulo-pagina">Panel de Gestión de Ofertas</h2>
            <p class="subtitulo-pagina">Administra tus publicaciones y revisa los candidatos inscritos en Gijón.</p>
        </header>

        {{-- Lógica de estadísticas --}}
        @php
            $total_ofertas = $ofertas->count();
            $total_inscritos = $ofertas->sum('inscripciones_count');
            $media_inscritos = $total_ofertas > 0 ? round($total_inscritos / $total_ofertas, 1) : 0;
        @endphp

        <div class="panel-estadisticas">
            <div class="tarjeta-est">
                <div class="icono-est azul">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div class="info-est">
                    <span class="cifra-est">{{ $total_ofertas }}</span>
                    <span class="etiqueta-est">Ofertas Activas</span>
                </div>
            </div>

            <div class="tarjeta-est">
                <div class="icono-est rojo">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="info-est">
                    <span class="cifra-est">{{ $total_inscritos }}</span>
                    <span class="etiqueta-est">Candidatos Totales</span>
                </div>
            </div>

            <div class="tarjeta-est">
                <div class="icono-est verde">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div class="info-est">
                    <span class="cifra-est">{{ $media_inscritos }}</span>
                    <span class="etiqueta-est">Media Inscritos</span>
                </div>
            </div>
        </div>

        <div class="zona-filtros">
            <div class="buscador-caja">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="filtroPuesto" placeholder="Buscar por título de oferta...">
            </div>
        </div>

        <div class="contenedor-tabla-gestion">
            @if ($total_ofertas > 0)
                <table class="tabla-gestion">
                    <thead>
                        <tr>
                            <th>Título de la Oferta</th>
                            <th>Salario</th>
                            <th>Jornada</th>
                            <th>Experiencia</th>
                            <th>Inscritos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ofertas as $o)
                            <tr>
                                <td>
                                    <div class="info-puesto">
                                        <strong class="nombre-puesto">{{ $o->titulo }}</strong>
                                        <span class="fecha-publicacion">Publicada el: {{ $o->fecha_oferta->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td><span class="dato-tabla">{{ $o->salario ? $o->salario . ' €' : 'No definido' }}</span></td>
                                <td><span class="dato-tabla">{{ $o->jornada ?? 'N/A' }}</span></td>
                                <td><span class="dato-tabla">{{ $o->experiencia ?? 'N/A' }}</span></td>
                                <td>
                                    <span class="badge-contador">
                                        <i class="fa-solid fa-users"></i> {{ $o->inscripciones_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="grupo-acciones">
                                        <a href="{{ route('ofertas.candidatos', $o->id) }}" class="btn-ver-candidatos">
                                            <i class="fa-solid fa-eye"></i> Ver Candidatos
                                        </a>
                                        <form id="form-borrar-{{ $o->id }}" action="{{ route('ofertas.destroy', $o->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-borrar-oferta"
                                                onclick="confirmarBorradoEmpresa('form-borrar-{{ $o->id }}', '{{ addslashes($o->titulo) }}')"
                                                style="border:none; background:none; cursor:pointer;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="panel-vacio">
                    <i class="fa-solid fa-briefcase fa-3x"></i>
                    <p>Aún no has publicado ninguna oferta de empleo.</p>
                    <a href="{{ route('ofertas.create') }}" class="btn-primario">Publicar mi primera oferta</a>
                </div>
            @endif
        </div>
    </section>
@endsection