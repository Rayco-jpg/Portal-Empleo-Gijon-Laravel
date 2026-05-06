@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="perfil-empresa-wrapper">
            <div class="contenido-perfil">
                <div class="row align-items-end header-perfil">
                    <div class="col-auto">
                        <img src="{{ $empresa->logo ? asset('storage/' . $empresa->logo) : asset('images/default-logo.png') }}"
                            class="logo-empresa-img" alt="Logo">
                    </div>
                    <div class="col info-principal">
                        <h1 class="nombre-empresa">
                            {{ $empresa->nombre_empresa }}
                            @if($empresa->usuario && $empresa->usuario->es_premium)
                                <span class="badge-premium">
                                    <i class="fa-solid fa-crown"></i> PREMIUM
                                </span>
                            @endif
                        </h1>
                        <p class="sector-empresa"><i class="fa-solid fa-briefcase"></i>
                            {{ $empresa->sector ?? 'Sector no definido' }}</p>
                        <p class="ubicacion-empresa"><i class="fa-solid fa-location-dot"></i>
                            {{ $empresa->ubicacion ?? 'Ubicación no especificada' }}</p>
                    </div>
                    <div class="col-md-auto acciones-empresa">
                        @if($empresa->sitio_web)
                            <a href="{{ $empresa->sitio_web }}" target="_blank" class="btn btn-web">Visitar Web</a>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <section class="seccion-descripcion">
                            <h4 class="titulo-seccion">Sobre nosotros</h4>
                            <div class="texto-descripcion">
                                {{ $empresa->descripcion ?? 'Esta empresa aún no ha añadido una descripción detallada.' }}
                            </div>
                        </section>

                        <section class="seccion-ofertas mt-5">
                            <h4 class="titulo-seccion">Ofertas de empleo activas</h4>
                            <div class="row">
                                @forelse($ofertas as $oferta)
                                    <div class="col-md-6 mb-3">
                                        <div class="card-oferta-empresa">
                                            <h5 class="titulo-oferta">{{ $oferta->titulo }}</h5>
                                            <p class="fecha-oferta">
                                                <i class="fa-regular fa-clock"></i> Publicada
                                                {{ $oferta->fecha_oferta ? $oferta->fecha_oferta->diffForHumans() : 'Recientemente' }}
                                            </p>
                                            <a href="{{ route('ofertas.show', $oferta->id) }}" class="btn btn-ver-oferta">Ver
                                                detalles</a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="sin-ofertas">
                                            <p>Actualmente no hay vacantes disponibles.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-4">
                        <aside class="sidebar-info">
                            <h5 class="titulo-sidebar">Información clave</h5>

                            <div class="dato-clave">
                                <label>Tamaño de la empresa</label>
                                <span>
                                    <i class="fa-solid fa-users"></i>
                                    {{ $empresa->tamano ?? 'No especificado' }}

                                    @if($empresa->tamano == 'Pequeña')
                                        (1-10 empleados)
                                    @elseif($empresa->tamano == 'Mediana')
                                        (11-50 empleados)
                                    @elseif($empresa->tamano == 'Grande')
                                        (+50 empleados)
                                    @endif
                                </span>
                            </div>

                            <div class="dato-clave">
                                <label>Contacto directo</label>
                                <span><i class="fa-solid fa-envelope"></i> {{ $empresa->usuario->email }}</span>
                            </div>

                            <div class="dato-social">
                                <label>Redes Sociales</label>
                                <div class="iconos-sociales">
                                    @if($empresa->twitter)
                                        <a href="{{ $empresa->twitter }}" target="_blank" class="social-link">
                                            <i class="fa-brands fa-x-twitter"></i>
                                        </a>
                                    @endif
                                    @if($empresa->facebook)
                                        <a href="{{ $empresa->facebook }}" target="_blank" class="social-link">
                                            <i class="fa-brands fa-facebook"></i>
                                        </a>
                                    @endif
                                    @if($empresa->instagram)
                                        <a href="{{ $empresa->instagram }}" target="_blank" class="social-link">
                                            <i class="fa-brands fa-instagram"></i>
                                        </a>
                                    @endif
                                    @if($empresa->whatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $empresa->whatsapp) }}"
                                            target="_blank" class="social-link">
                                            <i class="fa-brands fa-whatsapp"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </aside>

                        <div class="cta-contacto">
                            <p>¿Te interesa trabajar aquí?</p>
                            <a href="mailto:{{ $empresa->usuario->email }}" class="btn btn-contacto-directo">Enviar un
                                mensaje</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection