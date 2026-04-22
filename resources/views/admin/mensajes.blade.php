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
                                <span class="user-email">{{ $mensaje->user->email ?? 'Anónimo' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ strtolower($mensaje->asunto) }}">
                                    {{ strtoupper($mensaje->asunto) }}
                                </span>

                                @if(strtolower($mensaje->asunto) === 'oferta')
                                    @php
                                        preg_match('/ID: (\d+)/', $mensaje->mensaje, $matches);
                                        $ofertaId = $matches[1] ?? null;
                                    @endphp

                                    @if($ofertaId)
                                        <div style="margin-top: 8px;">
                                            <a href="{{ route('ofertas.show', $ofertaId) }}" target="_blank"
                                                class="btn-ver-oferta-reportada">
                                                <i class="fas fa-external-link-alt"></i> Ver Oferta
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="td-mensaje">
                                <div class="td-mensaje-texto">
                                    @if(strtolower($mensaje->asunto) === 'oferta')
                                        <i class="fas fa-exclamation-triangle icono-advertencia"></i>
                                        @php
                                            $mensajeLimpio = preg_replace('/\(ID: \d+\): /', '', $mensaje->mensaje);
                                        @endphp
                                        {{ $mensajeLimpio }}
                                    @else
                                        {{ $mensaje->mensaje }}
                                    @endif
                                </div>
                            </td>
                            <td class="td-fecha">
                                {{ $mensaje->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-center">
                                @if(!$mensaje->leido)
                                    <form action="{{ route('admin.mensajes.leido', $mensaje->id) }}" method="POST"
                                        class="inline-form">
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