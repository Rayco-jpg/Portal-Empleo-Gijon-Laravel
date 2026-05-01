@extends('layouts.app')

@section('title', '¡Pago Completado!')

@section('content')
<div class="contenedor-exito">
    <div class="tarjeta-exito">
        <div class="exito-header">
            <div class="icon-circle">
                <i class="fa-solid fa-check"></i>
            </div>
            <h1>¡Gracias por hacerte Premium!</h1>
            <p class="subtitulo">Tu suscripción ha sido activada con éxito. Ya puedes disfrutar de todas las ventajas exclusivas.</p>
        </div>
        
        <div class="factura-resumen">
            <h3>Tu nueva factura generada</h3>
            <div class="tabla-wrapper">
                <table class="tabla-moderna">
                    <thead>
                        <tr>
                            <th>Referencia</th>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th class="texto-derecha">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturas->take(1) as $factura)
                        <tr>
                            <td class="ref-factura">{{ $factura->referencia }}</td>
                            <td>{{ $factura->created_at->format('d/m/Y') }}</td>
                            <td>{{ $factura->concepto }}</td>
                            <td class="importe-bold texto-derecha">{{ number_format($factura->importe, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="acciones-finales">
            <a href="{{ route('premium.index') }}" class="btn-premium-gradiente">
                Ir al Panel Premium
            </a>
        </div>
    </div>
</div>
@endsection