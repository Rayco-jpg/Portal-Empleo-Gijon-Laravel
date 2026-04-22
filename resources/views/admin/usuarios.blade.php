@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
    <div class="panel-administracion">
        <header>
            <h1 class="titulo-pagina">Gestión de Usuarios</h1>
            <p class="subtitulo-pagina">Control y moderación de cuentas registradas en el sistema.</p>
        </header>

        <nav class="menu-navegacion">
            <a href="{{ route('admin.index') }}" class="enlace-menu">Inicio</a>
            <a href="{{ route('admin.usuarios') }}" class="enlace-menu activo">Usuarios</a>
            <a href="{{ route('admin.ofertas') }}" class="enlace-menu">Ofertas de Trabajo</a>
        </nav>

        <div class="contenedor-tabla">
            <table class="tabla-gestion">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Correo Electrónico</th>
                        <th>Tipo de Cuenta</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td><strong>#{{ $usuario->id }}</strong></td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @if($usuario->tipo_usuario == 'admin')
                                    <span class="etiqueta etiqueta-admin">Administrador</span>
                                @elseif($usuario->tipo_usuario == 'empresa')
                                    <span class="etiqueta etiqueta-empresa">Empresa</span>
                                @else
                                    <span class="etiqueta etiqueta-candidato">Candidato</span>
                                @endif
                            </td>
                            <td>
                                @if($usuario->fecha)
                                    {{ $usuario->fecha->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </td>
                            <td>
                                @if($usuario->tipo_usuario != 'admin')
                                    <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="boton-borrar">
                                            <i class="fa-solid fa-trash"></i> Borrar
                                        </button>
                                    </form>
                                @else
                                    <span class="texto-protegido"><i class="fa-solid fa-lock"></i> Sistema</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection