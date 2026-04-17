@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="panel-administracion">
        <header>
            <h1 class="titulo-pagina">Moderación de Ofertas</h1>
            <p class="subtitulo-pagina">Gestiona y supervisa las vacantes publicadas por las empresas.</p>
        </header>

        <nav class="menu-navegacion">
            {{-- Botón para volver al Dashboard de estadísticas --}}
            <a href="{{ route('admin.index') }}" class="enlace-menu">Inicio</a>
            
            {{-- Enlace a la gestión de usuarios --}}
            <a href="{{ route('admin.usuarios') }}" class="enlace-menu">Usuarios</a>
            
            {{-- Este es el botón activo en esta vista --}}
            <a href="{{ route('admin.ofertas') }}" class="enlace-menu activo">Ofertas de Trabajo</a>
        </nav>

        <div class="contenedor-tabla">
            <table class="tabla-gestion">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título de la Oferta</th>
                        <th>Empresa</th>
                        <th>Publicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ofertas as $oferta)
                        <tr>
                            <td><strong>#{{ $oferta->id }}</strong></td>
                            <td><span class="titulo-oferta">{{ $oferta->titulo }}</span></td>
                            <td>
                                <span class="etiqueta etiqueta-empresa">
                                    {{ $oferta->datosEmpresa->nombre_empresa ?? 'Empresa Desconocida' }}
                                </span>
                            </td>
                            <td>
                                {{ $oferta->fecha_oferta ? $oferta->fecha_oferta->format('d/m/Y') : 'Sin fecha' }}
                            </td>
                            <td>
                                <a href="{{ route('ofertas.show', $oferta->id) }}" class="boton-ver" target="_blank">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>

                                <form action="{{ route('admin.ofertas.destroy', $oferta->id) }}" method="POST"
                                    style="display:inline;" onsubmit="return confirm('¿Eliminar esta oferta permanentemente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="boton-borrar">
                                        <i class="fa-solid fa-trash"></i> Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($ofertas->isEmpty())
                <div class="mensaje-vacio" style="padding: 40px; text-align: center; color: #666;">
                    <i class="fa-solid fa-folder-open"></i> Actualmente no hay ofertas publicadas para moderar.
                </div>
            @endif
        </div>
    </div>
@endsection