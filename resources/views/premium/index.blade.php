@extends('layouts.app')

@section('content')
<div class="tarjeta-premium">
    <div class="cabecera-premium">
        <div class="circulo-icono">
            <i class="fa-solid fa-crown"></i>
        </div>
        <div class="texto-cabecera">
            <h1>Portal Empleo Premium</h1>
            <p class="subtitulo-premium">Lleva tu perfil al siguiente nivel</p>
        </div>
    </div>

    <div class="cuerpo-premium">
        @if(!$user->es_premium)
            <div class="seccion-ventas">
                <div class="info-principal">
                    @if($user->tipo_usuario === 'empresa')
                        <h2>Plan Empresas Pro</h2>
                        <p>Optimiza tus procesos de selección y contacta con los mejores perfiles de forma directa.</p>
                    @else
                        <h2>Impulsa tu carrera</h2>
                        <p>Destaca sobre la competencia y aumenta tus posibilidades de ser contratado.</p>
                    @endif
                </div>

                <div class="grid-beneficios">
                    @if($user->tipo_usuario === 'empresa')
                        <div class="card-beneficio">
                            <div class="icon-box blue"><i class="fa-solid fa-bullhorn"></i></div>
                            <div class="texto">
                                <strong>Ofertas Destacadas</strong>
                                <p>Tus vacantes siempre en las primeras posiciones.</p>
                            </div>
                        </div>
                        <div class="card-beneficio">
                            <div class="icon-box green"><i class="fa-solid fa-users-viewfinder"></i></div>
                            <div class="texto">
                                <strong>Talento Directo</strong>
                                <p>Acceso ilimitado a la base de datos de candidatos.</p>
                            </div>
                        </div>
                    @else
                        <div class="card-beneficio">
                            <div class="icon-box gold"><i class="fa-solid fa-bolt"></i></div>
                            <div class="texto">
                                <strong>Prioridad Máxima</strong>
                                <p>Tus inscripciones aparecen primero para las empresas.</p>
                            </div>
                        </div>
                        <div class="card-beneficio">
                            <div class="icon-box purple"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                            <div class="texto">
                                <strong>Perfil Resaltado</strong>
                                <p>Un diseño visual único que capta miradas.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="footer-accion">
                    <a href="{{ route('checkout') }}" class="btn-checkout">
                        <span class="btn-text">Activar Ahora</span>
                        <span class="btn-price">2.00€ / mes</span>
                    </a>
                    <p class="nota-pago">Pago seguro procesado por Stripe</p>
                </div>
            </div>
        @else
            {{-- VISTA: USUARIO PREMIUM ACTIVO --}}
            <div class="seccion-activa">
                <div class="status-badge">
                    <i class="fa-solid fa-check-double"></i> Plan Activo
                </div>

                <div class="info-activa-grid">
                    <div class="dato-suscripcion">
                        <span>Estado</span>
                        <strong class="text-premium">Premium</strong>
                    </div>
                    <div class="dato-suscripcion">
                        <span>Siguiente cobro</span>
                        <strong>{{ \Carbon\Carbon::parse($user->premium_hasta)->format('d/m/Y') }}</strong>
                    </div>
                </div>

                <div class="botones-gestion">
                    <a href="{{ route('premium.facturacion') }}" class="btn-gestion gestion-facturas">
                        <i class="fa-solid fa-receipt"></i> Mis Facturas
                    </a>
                    
                    <form action="{{ route('premium.cancelar') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-gestion gestion-cancelacion">
                            <i class="fa-solid fa-ban"></i> Cancelar Suscripción
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="link-footer">
    <a href="{{ route('perfil') }}">
        <i class="fa-solid fa-arrow-left"></i> Volver al panel
    </a>
</div>
@endsection