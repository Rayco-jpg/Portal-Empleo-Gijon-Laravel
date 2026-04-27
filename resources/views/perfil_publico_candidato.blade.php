@extends('layouts.app')

@section('title', 'Perfil de ' . ($perfil->nombre ?? $usuario->name))

@section('content')
    <div class="contenedor-perfil-publico">
        <div class="tarjeta-perfil-header">
            <div class="perfil-info-principal">
                <div class="foto-perfil-contenedor">
                    @if($perfil && $perfil->foto)
                        <img src="{{ asset('uploads/perfiles/' . $perfil->foto) }}" alt="Foto de {{ $usuario->name }}"
                            class="foto-perfil-img">
                    @else
                        <img src="{{ asset('assets/imagenes/default-user.png') }}" alt="Usuario" class="foto-perfil-img">
                    @endif
                </div>
                <div class="texto-cabecera">
                    {{-- CORRECCIÓN AQUÍ: Usamos perfil->nombre primero --}}
                    <h1>{{ $perfil->nombre ?? $usuario->name }} {{ $perfil->apellidos ?? '' }}</h1>
                    
                    <p class="ubicacion"><i class="fa-solid fa-location-dot"></i>
                        {{ $perfil->ubicacion ?? 'Gijón, Asturias' }}</p>
                    
                    <div class="etiquetas-perfil">
                        <span class="badge-tipo"><i class="fa-solid fa-user-tie"></i> Candidato</span>
                        @if($perfil && $perfil->habilidades_clave)
                            <span class="badge-habilidad"><i class="fa-solid fa-star"></i>
                                {{ trim(explode(',', $perfil->habilidades_clave)[0]) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="acciones-cabecera">
                <a href="mailto:{{ $usuario->email }}" class="btn-contacto-principal">
                    <i class="fa-solid fa-envelope"></i> Contactar por email
                </a>
            </div>
        </div>

        <div class="rejilla-perfil">
            <div class="columna-principal">
                <section class="seccion-blanca">
                    <h3><i class="fa-solid fa-address-card"></i> Sobre mí</h3>
                    <p class="texto-biografia">
                        {{ $perfil->biografia ?? 'El candidato no ha proporcionado una descripción detallada todavía.' }}
                    </p>
                </section>

                <section class="seccion-blanca">
                    <h3><i class="fa-solid fa-gears"></i> Habilidades y Competencias</h3>
                    <div class="contenedor-habilidades">
                        @if($perfil && $perfil->habilidades_clave && !empty(trim($perfil->habilidades_clave)))
                            @foreach(explode(',', $perfil->habilidades_clave) as $habilidad)
                                <span class="tag-habilidad">{{ trim($habilidad) }}</span>
                            @endforeach
                        @else
                            <p>No se han especificado habilidades.</p>
                        @endif
                    </div>
                </section>
            </div>

            <div class="columna-secundaria">
                <div class="seccion-blanca cv-box">
                    <h3><i class="fa-solid fa-file-pdf"></i> Currículum Vitae</h3>
                    @if($perfil && $perfil->curriculum)
                        <p>El candidato ha adjuntado su CV en formato PDF.</p>
                        <a href="{{ asset('uploads/curriculums/' . $perfil->curriculum) }}" class="btn-descargar-cv"
                            target="_blank">
                            <i class="fa-solid fa-download"></i> Descargar CV
                        </a>
                    @else
                        <p class="no-cv"><i class="fa-solid fa-triangle-exclamation"></i> No hay CV disponible.</p>
                    @endif
                </div>

                <div class="card-informacion">
                    <h4><i class="fa-solid fa-info-circle"></i> Información de interés</h4>

                    <div class="dato-perfil">
                        <i class="fa-solid fa-calendar-check"></i>
                        <strong>Miembro desde:</strong>
                        <span>{{ $usuario->created_at ? $usuario->created_at->format('M Y') : date('M Y') }}</span>
                    </div>

                    <div class="dato-perfil">
                        <i class="fa-solid fa-circle-dot {{ ($perfil->disponible ?? 1) == 1 ? 'text-success' : 'text-danger' }}"></i>
                        <strong>Estado:</strong>
                        <span class="badge-estado {{ ($perfil->disponible ?? 1) == 1 ? 'bg-success' : 'bg-danger' }}">
                            {{ ($perfil->disponible ?? 1) == 1 ? 'Disponible para trabajar' : 'No disponible' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection