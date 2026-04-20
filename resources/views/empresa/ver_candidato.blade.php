@extends('layouts.app')

@section('content')
@php
    $pendientes = $inscripciones->filter(fn($i) => strtolower($i->estado ?? '') == 'pendiente');
    $gestionados = $inscripciones->filter(fn($i) => strtolower($i->estado ?? '') != 'pendiente');
@endphp

<header class="cabecera-seccion">
    <h2 class="titulo-pagina"><i class="fa-solid fa-users-gear"></i> Gestión de Candidatos</h2>
    <p class="subtitulo-pagina">Revisa y gestiona los perfiles inscritos en tu oferta de empleo: <strong>{{ $oferta->titulo }}</strong></p>
</header>

<section class="bloque-candidatos card-shadow">
    <header class="bloque-header">
        <h3 class="subtitulo-seccion">
            <span class="punto-notificacion naranja"></span>
            Candidatos Nuevos ({{ $pendientes->count() }})
        </h3>
    </header>

    <div class="tabla-responsiva">
        <table class="tabla-gestion">
            <thead>
                <tr>
                    <th>Candidato</th>
                    <th>Inscrito el</th>
                    <th>Currículum</th>
                    <th>Iniciar Gestión</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendientes as $p)
                    <tr class="fila-candidato">
                        <td class="nombre-candidato">
                            <div class="contenedor-foto">
                                @if($p->candidato && $p->candidato->foto)
                                    <img src="{{ asset('uploads/perfiles/'.$p->candidato->foto) }}" alt="Perfil" class="foto-perfil-mini">
                                @else
                                    <div class="avatar-mini">{{ substr($p->candidato->nombre ?? 'U', 0, 1) }}</div>
                                @endif
                            </div>
                            <strong>{{ $p->candidato->nombre ?? 'Usuario' }} {{ $p->candidato->apellidos ?? '' }}</strong>
                        </td>
                        <td><i class="fa-regular fa-calendar-days"></i> {{ $p->fecha_inscripcion ? $p->fecha_inscripcion->format('d/m/Y') : 'N/A' }}</td>
                        <td>
                            @if($p->candidato && $p->candidato->curriculum)
                                <a href="{{ asset('uploads/curriculums/'.$p->candidato->curriculum) }}" target="_blank" class="enlace-pdf">
                                    <i class="fa-solid fa-file-pdf"></i> Ver PDF
                                </a>
                            @else
                                <span class="sin-documento">Sin documento</span>
                            @endif
                        </td>
                        <td class="celda-accion">
                            <form action="{{ route('inscripciones.actualizar_estado') }}" method="POST" class="form-cambio-estado">
                                @csrf
                                <input type="hidden" name="id_inscripcion" value="{{ $p->id }}">
                                <select name="nuevo_estado" onchange="this.form.submit()" class="select-estado">
                                    <option value="pendiente" selected disabled>Cambiar a...</option>
                                    <option value="revision">En Revisión</option>
                                    <option value="finalista">Pasar a Finalista</option>
                                    <option value="aceptado">Contratar</option>
                                    <option value="rechazado">Descartar</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="fila-vacia">
                        <td colspan="4">No hay nuevos candidatos sin revisar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="bloque-candidatos card-shadow">
    <header class="bloque-header">
        <h3 class="subtitulo-seccion">
            <span class="punto-notificacion azul"></span>
            Candidatos en Proceso / Gestionados ({{ $gestionados->count() }})
        </h3>
    </header>

    <div class="tabla-responsiva">
        <table class="tabla-gestion">
            <thead>
                <tr>
                    <th>Candidato</th>
                    <th>Estado Actual</th>
                    <th>Última Actividad</th>
                    <th>Actualizar Fase</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gestionados as $g)
                    <tr class="fila-gestionado">
                        <td class="nombre-candidato">
                            <div class="contenedor-foto">
                                @if($g->candidato && $g->candidato->foto)
                                    <img src="{{ asset('uploads/perfiles/'.$g->candidato->foto) }}" alt="Perfil" class="foto-perfil-mini">
                                @else
                                    <div class="avatar-mini">{{ substr($g->candidato->nombre ?? 'U', 0, 1) }}</div>
                                @endif
                            </div>
                            <strong>{{ $g->candidato->nombre ?? 'Usuario' }} {{ $g->candidato->apellidos ?? '' }}</strong>
                        </td>
                        <td class="estado-actual">
                            <span class="badge-estado {{ strtolower($g->estado) }}">
                                {{ ucfirst($g->estado) }}
                            </span>
                        </td>
                        <td><i class="fa-solid fa-clock-rotate-left"></i> {{ $g->updated_at ? $g->updated_at->format('d/m/Y H:i') : 'Sin fecha' }}</td>
                        <td class="celda-accion">
                            <form action="{{ route('inscripciones.actualizar_estado') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_inscripcion" value="{{ $g->id }}">
                                <select name="nuevo_estado" onchange="this.form.submit()" class="select-estado-mini">
                                    <option value="pendiente">Mover a Pendiente</option>
                                    <option value="revision" {{ strtolower($g->estado) == 'revision' ? 'selected' : '' }}>En Revisión</option>
                                    <option value="finalista" {{ strtolower($g->estado) == 'finalista' ? 'selected' : '' }}>Finalista</option>
                                    <option value="aceptado" {{ strtolower($g->estado) == 'aceptado' ? 'selected' : '' }}>Contratar</option>
                                    <option value="rechazado" {{ strtolower($g->estado) == 'rechazado' ? 'selected' : '' }}>Descartar</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="fila-vacia">
                        <td colspan="4">Aún no has gestionado ningún perfil.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<footer class="pie-seccion">
    <a href="{{ route('ofertas.index') }}" class="enlace-volver">
        <i class="fa-solid fa-arrow-left"></i> Volver a mis ofertas
    </a>
</footer>
@endsection