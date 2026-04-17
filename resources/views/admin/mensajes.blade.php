@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin_mensaje.css') }}">

<div class="contenedor-admin">
    <div class="admin-header">
        <h2><i class="fas fa-envelope-open-text"></i> Buzón de Soporte</h2>
        <p>Gestiona los reportes y dudas de los usuarios de la plataforma.</p>
    </div>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Asunto</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mensajes as $mensaje)
                    <tr class="{{ $mensaje->leido ? 'fila-leida' : '' }}">
                        <td>
                            <span class="user-email">{{ $mensaje->user->email }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $mensaje->asunto }}">
                                {{ strtoupper($mensaje->asunto) }}
                            </span>
                        </td>
                        <td class="td-mensaje" title="{{ $mensaje->mensaje }}">
                            {{ $mensaje->mensaje }}
                        </td>
                        <td class="td-fecha">
                            {{ $mensaje->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-center">
                            @if(!$mensaje->leido)
                                <form action="{{ route('admin.mensajes.leido', $mensaje->id) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-check" title="Marcar como leído">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @else
                                <span class="status-revisado">
                                    <i class="fas fa-check-double"></i> Revisado
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No hay mensajes pendientes en el buzón.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection