<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Empleo Gijón - @yield('title')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/alertas.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</head>

<body>
    <header class="cabecera-principal">
        <div class="contenedor-header">
            <div class="logotipo">
                <a href="{{ route('inicio') }}" class="enlace-logo">
                    <img src="{{ asset('assets/imagenes/logo_portal_empleo.png') }}" alt="Logo" class="logo-cabecera">
                </a>
                <p class="saludo-usuario">Hola,
                    <span class="nombre-destacado">
                        @auth
                            @if(Auth::user()->tipo_usuario == 'admin')
                                Administrador
                            @else
                                {{ session('nombre') ?? (Auth::user()->name ?? Auth::user()->nombre) }}
                            @endif
                        @else
                            Invitado
                        @endauth
                    </span>
                </p>
            </div>

            <nav class="navegacion-principal">
                @unless(Auth::user()?->tipo_usuario === 'empresa' || Auth::user()?->tipo_usuario === 'admin')
                    <a href="{{ route('buscador') }}"
                        class="enlace-nav {{ request()->routeIs('buscador') ? 'activo' : '' }}">
                        Buscador
                    </a>
                @endunless
                @auth
                    {{-- Solo se ejecuta si el usuario ha iniciado sesión --}}
                    @if(Auth::user()->tipo_usuario == 'candidato')
                        <a href="{{ route('favoritos.index') }}"
                            class="enlace-nav {{ request()->routeIs('favoritos.index') ? 'activo' : '' }}">Mis Favoritos</a>
                        <a href="{{ route('inscripciones.index') }}"
                            class="enlace-nav {{ request()->routeIs('inscripciones.index') ? 'activo' : '' }}">Mis
                            Inscripciones</a>

                    @elseif(Auth::user()->tipo_usuario == 'empresa')
                        <a href="{{ route('ofertas.index') }}"
                            class="enlace-nav {{ request()->routeIs('ofertas.index') ? 'activo' : '' }}">Panel de Ofertas</a>
                        <a href="{{ route('ofertas.create') }}"
                            class="enlace-nav {{ request()->routeIs('ofertas.create') ? 'activo' : '' }}">Publicar Oferta</a>

                    @elseif(Auth::user()->tipo_usuario == 'admin')
                        <a href="{{ route('admin.index') }}"
                            class="enlace-nav {{ request()->routeIs('admin.index') ? 'activo' : '' }}">Panel de control</a>
                        <a href="{{ route('admin.usuarios') }}"
                            class="enlace-nav {{ request()->routeIs('admin.usuarios') ? 'activo' : '' }}">Usuarios</a>
                        <a href="{{ route('admin.ofertas') }}"
                            class="enlace-nav {{ request()->routeIs('admin.ofertas') ? 'activo' : '' }}">Ofertas</a>
                        <a href="{{ route('admin.mensajes') }}"
                            class="enlace-nav {{ request()->routeIs('admin.mensajes') ? 'activo' : '' }}">Buzón de soporte</a>
                    @endif

                    @if(Auth::user()->tipo_usuario !== 'admin')
                        <a href="{{ route('perfil') }}"
                            class="enlace-nav {{ request()->routeIs('perfil') ? 'activo' : '' }}">Perfil</a>
                    @endif

                    <a href="{{ route('logout') }}" class="enlace-nav enlace-salir">Salir</a>
                @else
                    <a href="{{ route('login') }}" class="enlace-nav">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="enlace-nav">Registrarse</a>
                @endauth

                <button id="btn-tema" class="enlace-nav">
                    <i class="fa-solid fa-moon"></i>
                </button>
            </nav>
        </div>
    </header>

    <main class="contenedor-principal">
        @if(session('success') || session('error') || session('info') || $errors->any())
            <div class="contenedor-alertas" id="contenedor-alertas">
                @if(session('success'))
                    <div class="alerta alerta-exito">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alerta alerta-error">
                        <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alerta alerta-info">
                        <i class="fa-solid fa-circle-info"></i> {{ session('info') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alerta alerta-error">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer-global">
        <div class="footer-contenido">
            <div class="footer-seccion info">
                <h3 class="footer-logo">Gijón<span>Empleo</span></h3>
                <p>Tu portal de confianza para encontrar trabajo en Gijón. Conectando talento local con oportunidades
                    reales.</p>
            </div>

            <div class="footer-seccion links">
                <h4>Navegación</h4>
                <ul>
                    @if(Auth::check())
                        @if(Auth::user()->tipo_usuario == 'candidato')
                            <li><a href="{{ route('buscador') }}"><i class="fa-solid fa-angle-right"></i> Buscador de Empleo</a>
                            </li>
                            <li><a href="{{ route('inscripciones.index') }}"><i class="fa-solid fa-angle-right"></i> Mis
                                    Inscripciones</a></li>
                            <li><a href="{{ route('perfil') }}"><i class="fa-solid fa-angle-right"></i> Mi Perfil</a></li>
                        @elseif(Auth::user()->tipo_usuario == 'empresa')
                            <li>
                                <a href="{{ route('ofertas.index') }}">
                                    <i class="fa-solid fa-angle-right"></i> Panel de Ofertas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ofertas.create') }}">
                                    <i class="fa-solid fa-angle-right"></i> Publicar Oferta
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('perfil') }}">
                                    <i class="fa-solid fa-angle-right"></i> Mi Perfil de Empresa
                                </a>
                            </li>
                        @elseif(Auth::user()->tipo_usuario == 'admin')
                            <li>
                                <a href="{{ route('admin.index') }}">
                                    <i class="fa-solid fa-angle-right"></i> Panel de Control
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.usuarios') }}">
                                    <i class="fa-solid fa-angle-right"></i> Usuarios
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ofertas') }}">
                                    <i class="fa-solid fa-angle-right"></i> Ofertas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.mensajes') }}">
                                    <i class="fa-solid fa-angle-right"></i> Buzón de soporte
                                </a>
                            </li>
                        @endif
                    @else
                        <li><a href="{{ route('buscador') }}"><i class="fa-solid fa-angle-right"></i> Ver Ofertas</a></li>
                        <li><a href="{{ route('login') }}"><i class="fa-solid fa-angle-right"></i> Iniciar Sesión</a></li>
                        <li><a href="{{ route('register') }}"><i class="fa-solid fa-angle-right"></i> Registrarse</a></li>
                    @endif
                </ul>
            </div>

            <div class="footer-seccion contacto">
                <h4>Contacto</h4>
                <p><i class="fa-solid fa-location-dot"></i> Gijón, Asturias</p>
                <p><i class="fa-solid fa-envelope"></i> info@gijonempleo.es</p>
                <div class="contenedor-enlace-reporte">
                    <a href="{{ route('contacto') }}" class="enlace-reporte">
                        <i class="fa-solid fa-circle-exclamation"></i> Reportar un error o sugerencia
                    </a>
                </div>
            </div>

            <div class="footer-inferior">
                <p>&copy; 2026 Portal de Empleo Gijón - Proyecto Final de Grado</p>
            </div>
        </div>
    </footer>
</body>

</html>