@extends('layouts.app')

@section('content')
    @php
        // Lógica para el icono de estado de la inscripción
        $estado_slug = strtolower($oferta->estado_inscripcion ?? '');
        $iconos = [
            'aceptada' => 'fa-circle-check',
            'aceptado' => 'fa-circle-check',
            'rechazada' => 'fa-circle-xmark',
            'rechazado' => 'fa-circle-xmark',
            'pendiente' => 'fa-clock'
        ];
        $icono_status = $iconos[$estado_slug] ?? 'fa-circle-info';
    @endphp

    <section class="detalle-oferta tarjeta-blanca">
        <div id="datos-oferta" class="datos-ocultos" data-titulo="{{ $oferta->titulo }}"
            data-empresa="{{ $oferta->datosEmpresa->nombre_empresa ?? 'Empresa no disponible' }}"
            data-zona="{{ $oferta->zona_gijon }}"
            data-salario="{{ $oferta->salario ? $oferta->salario . ' €' : 'A convenir' }}"
            data-descripcion="{{ $oferta->descripcion }}" data-user-skills="{{ $mis_habilidades }}">
        </div>

        <header class="cabecera-detalle">
            <div class="navegacion-superior">
                @if(Auth::check() && Auth::user()->tipo_usuario == 'admin')
                    {{-- Si es admin, vuelve a su panel de control --}}
                    <a href="{{ route('admin.ofertas') }}" class="enlace-volver">
                        <i class="fa-solid fa-arrow-left"></i> Volver a Gestión de Ofertas
                    </a>
                @else
                    {{-- Si es candidato o invitado, vuelve al buscador --}}
                    <a href="{{ route('buscador') }}" class="enlace-volver">
                        <i class="fa-solid fa-arrow-left"></i> Volver al buscador
                    </a>
                @endif
            </div>

            <div class="titulo-principal-oferta">
                <h1 class="nombre-puesto">{{ $oferta->titulo }}</h1>
                <span class="badge-categoria">
                    {{ $oferta->categoriaRelacion->nombre_categoria ?? 'Sin categoría' }}
                </span>
            </div>
        </header>

        <div class="grid-informacion-oferta">
            <aside class="columna-datos">
                <div class="tarjeta-dato">
                    <i class="fa-solid fa-building icono-v"></i>
                    <strong>Empresa:</strong>
                    {{-- CAMBIO CLAVE: Nombre de la empresa corregido --}}
                    <span>{{ $oferta->datosEmpresa->nombre_empresa ?? 'Empresa no disponible' }}</span>
                </div>
                <div class="tarjeta-dato">
                    <i class="fa-solid fa-location-dot icono-v"></i>
                    <strong>Ubicación:</strong>
                    <span>{{ $oferta->zona_gijon }}</span>
                </div>
                <div class="tarjeta-dato">
                    <i class="fa-solid fa-money-bill-wave icono-v"></i>
                    <strong>Salario:</strong>
                    <span>{{ $oferta->salario ? $oferta->salario . ' €' : 'A convenir' }}</span>
                </div>
                <div class="tarjeta-dato">
                    <i class="fa-solid fa-clock icono-v"></i>
                    <strong>Jornada:</strong>
                    <span>{{ $oferta->jornada ?? 'No especificada' }}</span>
                </div>
                <div class="tarjeta-dato">
                    <i class="fa-solid fa-briefcase icono-v"></i>
                    <strong>Experiencia:</strong>
                    <span>{{ $oferta->experiencia ?? 'Sin especificar' }}</span>
                </div>
                <div class="tarjeta-dato">
                    <i class="fa-solid fa-calendar-days icono-v"></i>
                    <strong>Publicada:</strong>
                    <span>{{ \Carbon\Carbon::parse($oferta->fecha_oferta)->format('d/m/Y') }}</span>
                </div>

                <button onclick="prepararPDF()" class="boton-pdf-lateral">
                    <i class="fa-solid fa-file-pdf"></i> Descargar Ficha
                </button>
            </aside>

            <article class="columna-descripcion">
                <h3 class="subtitulo-seccion">Descripción del puesto</h3>
                <div class="texto-descripcion">
                    {!! nl2br(e($oferta->descripcion)) !!}
                </div>
            </article>
        </div>

        <footer class="pie-acciones-oferta">
            @if(Auth::user() && Auth::user()->tipo_usuario === 'candidato')
                @if($oferta->estado_inscripcion)
                    <div class="contenedor-estado-inscripcion">
                        <div class="mensaje-info-inscripcion">
                            <i class="fa-solid {{ $icono_status }}"></i>
                            <span>Ya estás inscrito en esta oferta.</span>
                            <span class="badge-estado estado-{{ $estado_slug }}">
                                {{ ucfirst($oferta->estado_inscripcion) }}
                            </span>
                        </div>

                        @if($estado_slug == 'pendiente')
                            <form action="{{ route('inscripciones.destroy') }}" method="POST" class="form-cancelar"
                                onsubmit="return confirm('¿Retirar candidatura?')">
                                @csrf
                                <input type="hidden" name="id_oferta" value="{{ $oferta->id }}">
                                <button type="submit" class="boton-secundario-borrar">
                                    <i class="fa-solid fa-trash-can"></i> Cancelar inscripción
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <form action="{{ route('inscripciones.postular') }}" method="POST" class="form-inscripcion">
                        @csrf
                        <input type="hidden" name="id_oferta" value="{{ $oferta->id }}">
                        <button type="submit" class="boton-primario-accion">
                            <i class="fa-solid fa-paper-plane"></i> Inscribirme en esta oferta
                        </button>
                    </form>
                @endif
            @else
                <div class="alerta-aviso">
                    <i class="fa-solid fa-circle-info"></i>
                    <p>Debes estar identificado como candidato para poder inscribirte en ofertas.</p>
                </div>
            @endif
        </footer>
    </section>
    @if(Auth::check() && Auth::user()->tipo_usuario == 'admin')
        <div class="panel-moderacion-admin">
            <div>
                <h3><i class="fa-solid fa-shield-halved"></i> Panel de Moderación</h3>
                <p>Como administrador, tienes permisos para eliminar esta oferta si incumple las normas.</p>
            </div>

            <form action="{{ route('admin.ofertas.destroy', $oferta->id) }}" method="POST"
                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta oferta permanentemente?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-admin-eliminar">
                    <i class="fa-solid fa-trash-can"></i> Eliminar Oferta
                </button>
            </form>
        </div>
    @endif
@endsection