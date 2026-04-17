@extends('layouts.app')

@section('content')
    <section class="seccion-mis-postulaciones">
        <header class="cabecera-seccion">
            <h2 class="titulo-pagina">
                <i class="fa-solid fa-file-lines"></i> Mis Inscripciones
            </h2>
            <p class="descripcion-seccion">Seguimiento en tiempo real de tus solicitudes de empleo en Gijón:</p>
        </header>

        <div class="contenedor-tabla-postulaciones">
            @if($postulaciones->count() > 0)
                <table class="tabla-gestion">
                    <thead>
                        <tr>
                            <th class="col-oferta">Oferta de Empleo</th>
                            <th class="col-empresa">Empresa</th>
                            <th class="col-condiciones">Condiciones</th>
                            <th class="col-fecha">Fecha Inscripción</th>
                            <th class="col-estado">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($postulaciones as $p)
                            @php
                                $estado = strtolower(trim($p->estado ?? 'pendiente'));
                                $icono_estado = match ($estado) {
                                    'pendiente' => 'fa-clock',
                                    'aceptada', 'seleccionado', 'aceptado' => 'fa-circle-check',
                                    'rechazada', 'rechazado' => 'fa-circle-xmark',
                                    default => 'fa-circle-question',
                                };
                            @endphp
                            <tr class="fila-postulacion">
                                <td class="celda-oferta">
                                    <span class="titulo-oferta-link">{{ $p->oferta?->titulo ?? 'Oferta no disponible' }}</span>
                                    <div class="detalle-ubicacion-mini">
                                        <i class="fa-solid fa-location-dot icono-v"></i>
                                        <span class="nombre-zona">{{ $p->oferta?->zona_gijon ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="celda-empresa">
                                    <span class="nombre-entidad">
                                        <i class="fa-solid fa-building icono-v"></i>
                                        {{ $p->oferta?->datosEmpresa?->nombre_empresa ?? 'Empresa no disponible' }}
                                    </span>
                                </td>
                                <td class="celda-condiciones">
                                    <div class="lista-condiciones-mini">
                                        @if($p->oferta)
                                            <span><i class="fa-solid fa-money-bill-wave"></i>
                                                {{ $p->oferta->salario ?? '---' }}€</span><br>
                                            <span><i class="fa-solid fa-clock"></i> {{ $p->oferta->jornada ?? '---' }}</span><br>
                                            <span><i class="fa-solid fa-briefcase"></i> {{ $p->oferta->experiencia ?? '---' }}</span>
                                        @else
                                            <span class="text-muted">Sin detalles</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="celda-fecha">
                                    <span class="fecha-texto">
                                        <i class="fa-regular fa-calendar-check"></i>
                                        @if($p->fecha_inscripcion instanceof \Carbon\Carbon)
                                            {{ $p->fecha_inscripcion->format('d/m/Y') }}
                                        @else
                                            {{ date('d/m/Y', strtotime($p->fecha_inscripcion)) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="celda-estado">
                                    <div class="contenedor-acciones-estado">
                                        <span class="badge-estado-candidato estado-{{ $estado }}">
                                            <i class="fa-solid {{ $icono_estado }}"></i>
                                            {{ ucfirst($estado) }}
                                        </span>

                                        {{-- El botón de cancelar solo aparece si está pendiente --}}
                                        @if($estado == 'pendiente')
                                            {{-- Quitamos el @method('DELETE') para que coincida con tu web.php --}}
                                            <form action="{{ route('inscripciones.destroy') }}" method="POST"
                                                onsubmit="return confirm('¿Retirar candidatura?')">
                                                @csrf
                                                <input type="hidden" name="id_oferta" value="{{ $p->id_oferta }}">
                                                <button type="submit" class="btn-cancelar-link" title="Cancelar inscripción"
                                                    style="background:none; border:none; color:red; cursor:pointer;">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="contenedor-aviso-vacio">
                    <div class="caja-mensaje">
                        <i class="fa-solid fa-circle-exclamation fa-3x icono-aviso"></i>
                        <p class="texto-vacio">Aún no te has inscrito en ninguna oferta de trabajo.</p>
                    </div>
                    <a href="{{ route('buscador') }}" class="boton-primario-accion">
                        <i class="fa-solid fa-magnifying-glass"></i> Explorar ofertas disponibles
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection