@extends('layouts.app')

@section('title', 'Mis Favoritos')

@section('content')
<section class="seccion-favoritos">
    <header class="cabecera-vistas">
        <h2 class="titulo-pagina">
            <i class="fa-solid fa-heart icono-favorito-titulo"></i> Mis Ofertas Guardadas
        </h2>
        <p class="subtitulo-pagina">Gestiona las ofertas que has marcado como interesantes.</p>
    </header>

    <div class="cuadricula-ofertas">
        @if(count($favoritos) > 0)
            @foreach($favoritos as $o)
                <a href="{{ route('ofertas.show', $o->id) }}" class="tarjeta-link-wrapper">
                    <article class="tarjeta-oferta">
                        <div class="cuerpo-tarjeta">
                            <div class="fila-superior">
                                <span class="etiqueta-categoria">{{ $o->nombre_categoria }}</span>
                                
                                {{-- Botón para quitar de favoritos --}}
                                <div class="btn-fav-corazon" 
                                     onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('favoritos.toggle', $o->id) }}';">
                                    <i class="fa-solid fa-heart icono-corazon-activo"></i>
                                </div>
                            </div>

                            <h3 class="titulo-oferta">{{ $o->titulo }}</h3>
                            
                            <div class="datos-breves">
                                <p class="dato-linea"><i class="fa-solid fa-building"></i> <strong>{{ $o->nombre_empresa }}</strong></p>
                                <p class="dato-linea"><i class="fa-solid fa-location-dot"></i> {{ $o->zona_gijon }}</p>
                                <p class="dato-linea"><i class="fa-solid fa-money-bill-wave"></i> {{ $o->salario ? $o->salario . ' €' : 'A convenir' }}</p>
                            </div>
                        </div>
                        <footer class="pie-tarjeta">
                            <span class="boton-ver-detalles-falso">Ver detalles <i class="fa-solid fa-arrow-right"></i></span>
                        </footer>
                    </article>
                </a>
            @endforeach
        @else
            <div class="mensaje-vacio">
                <i class="fa-regular fa-heart icono-vacio-grande"></i>
                <p>No tienes ofertas guardadas todavía.</p>
                <a href="{{ route('buscador') }}" class="boton-buscar-principal">
                    Ir al buscador
                </a>
            </div>
        @endif
    </div>
</section>
@endsection