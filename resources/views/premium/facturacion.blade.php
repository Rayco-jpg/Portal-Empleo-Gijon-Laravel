@extends('layouts.app')

@section('title', 'Facturación y Pagos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/facturas.css') }}">
@endpush

@section('content')
<div class="tarjeta-premium">
    <div class="cabecera-facturas">
        <h1><i class="fa-solid fa-file-invoice-dollar"></i> Mis Facturas</h1>
    </div>

    <div class="cuerpo-premium">
        <div class="contenedor-tabla">
            <table class="tabla-facturas">
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Importe</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                        <tr>
                            <td class="ref-factura">#{{ $factura->referencia }}</td>
                            <td>{{ $factura->created_at->format('d/m/Y') }}</td>
                            <td>{{ $factura->concepto }}</td>
                            <td class="importe-factura">{{ number_format($factura->importe, 2) }} €</td>
                            <td>
                                <span class="badge-estado {{ $factura->estado }}">
                                    {{ ucfirst($factura->estado) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="tabla-vacia">
                                No tienes facturas registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="pie-facturacion">
            <a href="{{ route('premium.index') }}" class="boton-volver">
                <i class="fa-solid fa-arrow-left"></i> Volver a Suscripción
            </a>
        </div>
    </div>
</div>
@endsection