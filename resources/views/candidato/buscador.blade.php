@extends('layouts.app')

@section('content')
    <section class="seccion-buscador">
        <header class="cabecera-buscador">
            <h2 class="titulo-pagina">Ofertas de empleo en Gijón</h2>
            <p class="subtitulo-pagina">Explora las últimas oportunidades publicadas en la ciudad.</p>

            <form action="{{ route('buscador') }}" method="GET" class="form-filtros-empleo">
                <div class="fila-filtros">
                    <input type="text" name="puestos" placeholder="Puesto o empresa..." value="{{ request('puestos') }}"
                        class="input-texto">

                    <select name="zona" class="select-personalizado">
                        <option value="">Todas las zonas</option>
                        @foreach(['Centro', 'El Llano', 'La Arena', 'Natahoyo', 'Pumarín', 'Viesques', 'Somió'] as $b)
                            <option value="{{ $b }}" {{ request('zona') == $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>

                    <select name="jornada" class="select-personalizado">
                        <option value="">Cualquier jornada</option>
                        <option value="Completa" {{ request('jornada') == 'Completa' ? 'selected' : '' }}>Completa</option>
                        <option value="Parcial" {{ request('jornada') == 'Parcial' ? 'selected' : '' }}>Parcial</option>
                    </select>

                    <button type="submit" class="boton-buscar-principal">
                        <i class="fa-solid fa-magnifying-glass"></i> Filtrar Resultados
                    </button>
                </div>
            </form>
        </header>

        {{-- Banner de Novedades --}}
        @if(isset($hay_novedades) && $hay_novedades)
            <div class="banner-novedades-alerta {{ request('ver_novedades') ? 'filtro-activo' : '' }}" id="bannerAlerta">
                <div class="contenido-banner" onclick="window.location.href='{{ route('buscador', ['ver_novedades' => 1]) }}';">
                    <i class="fa-solid fa-bell-concierge"></i>
                    <span>
                        @if(request('ver_novedades'))
                            Mostrando las <strong>{{ $conteo_nuevas }}</strong> novedades en
                            <strong>{{ $nombre_cat_alerta }}</strong>.
                            <a href="{{ route('buscador') }}" class="enlace-volver">Ver todas</a>
                        @else
                            ¡Atención! Hay <strong>{{ $conteo_nuevas }}</strong> ofertas nuevas de hoy en:
                            <strong>{{ $nombre_cat_alerta }}</strong>.
                            <small>(Pulsa para ver)</small>
                        @endif
                    </span>
                </div>
                <button class="btn-cerrar-banner"
                    onclick="event.stopPropagation(); document.getElementById('bannerAlerta').style.display='none'">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        {{-- Tarjetas de Estadísticas --}}
        <div class="contenedor-estadisticas">
            @auth
                <div class="tarjeta-stat">
                    <div class="icono-stat azul"><i class="fa-solid fa-file-signature"></i></div>
                    <div class="info-stat">
                        <span class="numero-stat">{{ $stats['inscripciones'] ?? 0 }}</span>
                        <span class="etiqueta-stat">Postulaciones</span>
                    </div>
                </div>
                <div class="tarjeta-stat">
                    <div class="icono-stat rosa"><i class="fa-solid fa-heart"></i></div>
                    <div class="info-stat">
                        <span class="numero-stat">{{ $stats['favoritos'] ?? 0 }}</span>
                        <span class="etiqueta-stat">Favoritos</span>
                    </div>
                </div>
                <div class="tarjeta-stat">
                    <div class="icono-stat verde"><i class="fa-solid fa-eye"></i></div>
                    <div class="info-stat">
                        <span class="numero-stat">{{ $stats['visitas'] ?? 0 }}</span>
                        <span class="etiqueta-stat">Visitas perfil</span>
                    </div>
                </div>
            @endauth
            @guest
                <div class="aviso-vinculo-registro">
                    <div class="cuerpo-vinculo">
                        <div class="icono-destacado">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <div class="texto-informativo">
                            <h4 class="titulo-destacado">Potencia tu perfil profesional</h4>
                            <p class="parrafo-secundario">Regístrate para guardar ofertas, gestionar candidaturas y mejorar tu
                                visibilidad.</p>
                        </div>
                    </div>

                    <div class="grupo-acciones">
                        <a href="{{ route('login') }}" class="boton-interaccion secundario">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="boton-interaccion primario">Crear Cuenta</a>
                    </div>
                </div>
            @endguest
        </div>

        {{-- Mapa --}}
        <div class="mapa-buscador-container">
            <h3 class="mapa-titulo">
                <i class="fa-solid fa-map-location-dot"></i> Ubicación de las ofertas en Gijón
            </h3>
            <div id="map" style="height: 400px; border-radius: 12px;"></div>
        </div>

        {{-- Cuadrícula de Ofertas --}}
        <div class="cuadricula-ofertas">
            @forelse($ofertas as $o)
                <a href="{{ route('ofertas.show', $o->id) }}" class="tarjeta-link-wrapper">
                    <article class="tarjeta-oferta">
                        <span class="etiqueta-categoria">{{ $o->categoriaRelacion->nombre_categoria ?? 'Sin Categoría' }}</span>

                        <div class="cuerpo-tarjeta">
                            <div class="fila-superior">
                                <div class="actions-header-tarjeta">
                                    <form action="{{ route('favoritos.toggle', $o->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-fav-corazon" onclick="event.stopPropagation();"
                                            style="background:none; border:none; padding:0; cursor:pointer;">
                                            <i
                                                class="fa-heart {{ (isset($o->es_favorito) && $o->es_favorito) ? 'fa-solid icono-corazon-activo' : 'fa-regular icono-corazon-inactivo' }}"></i>
                                        </button>
                                    </form>
                                    <span class="fecha-publicacion">
                                        <i class="fa-regular fa-calendar-days"></i>
                                        {{ date('d/m/Y', strtotime($o->fecha_oferta)) }}
                                    </span>
                                </div>
                            </div>
                            <h3 class="titulo-oferta">{{ $o->titulo }}</h3>
                            <div class="datos-breves">
                                <p class="dato-linea"><i class="fa-solid fa-building"></i>
                                    <strong>{{ $o->datosEmpresa->nombre_empresa ?? 'Empresa no disponible' }}</strong>
                                </p>
                                <p class="dato-linea"><i class="fa-solid fa-location-dot"></i> {{ $o->zona_gijon }}</p>
                                <p class="dato-linea"><i class="fa-solid fa-money-bill-wave"></i> <span>Salario:
                                        {{ $o->salario ? $o->salario . ' €' : 'A convenir' }}</span></p>
                                <p class="dato-linea"><i class="fa-solid fa-clock"></i>
                                    <span>{{ $o->jornada ?? 'No especificada' }}</span>
                                </p>
                            </div>
                        </div>
                        <footer class="pie-tarjeta">
                            @if(isset($o->estado_inscripcion) && $o->estado_inscripcion !== null)
                                @php
                                    $estado = strtolower(trim($o->estado_inscripcion));
                                    $clase_progreso = match ($estado) {
                                        'pendiente' => 'progreso-1',
                                        'revision' => 'progreso-2',
                                        'finalista' => 'progreso-3',
                                        'aceptado', 'aceptada' => 'progreso-4',
                                        'rechazado', 'rechazada' => 'progreso-rechazado',
                                        default => 'progreso-1',
                                    };
                                @endphp

                                <div class="timeline-mini {{ $clase_progreso }}">
                                    <div class="punto activo" title="Recibida"></div>
                                    <div class="punto {{ in_array($estado, ['revision', 'finalista', 'aceptado', 'aceptada', 'rechazado', 'rechazada']) ? 'activo' : '' }}"
                                        title="Revisión"></div>
                                    <div class="punto {{ in_array($estado, ['finalista', 'aceptado', 'aceptada']) ? 'activo' : '' }}"
                                        title="Finalista"></div>
                                    <div class="punto {{ in_array($estado, ['aceptado', 'aceptada']) ? 'activo' : '' }}"
                                        title="¡Seleccionado!"></div>
                                </div>
                                <span class="texto-estado estado-{{ $estado }}">{{ ucfirst($estado) }}</span>
                            @else
                                <span class="boton-ver-detalles">Ver detalles <i class="fa-solid fa-arrow-right"></i></span>
                            @endif
                        </footer>
                    </article>
                </a>
            @empty
                <div class="mensaje-vacio">
                    <i class="fa-solid fa-magnifying-glass-chart fa-3x"></i>
                    <p>No se han encontrado ofertas.</p>
                    <a href="{{ route('buscador') }}" class="btn-limpiar">Limpiar filtros</a>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Scripts del Mapa --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Esta variable global es la que leerá el archivo JS externo
        window.DATOS_MAPA_BUSCADOR = @json($puntosMapa ?? []);
    </script>

    <script src="{{ asset('js/mapa_buscador.js') }}"></script>
@endsection