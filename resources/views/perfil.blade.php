@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
    <section class="perfil-usuario shadow-lg">
        <header class="cabecera-perfil-flexible">
            <div class="contenedor-foto-perfil">
                @if($perfil->foto)
                    <img src="{{ asset('uploads/perfiles/' . $perfil->foto) }}?v={{ time() }}" alt="Foto de perfil"
                        class="foto-avatar">
                @else
                    <div class="avatar-vacio">
                        <i class="fa-solid {{ $user->tipo_usuario == 'candidato' ? 'fa-user-tie' : 'fa-building' }} fa-3x"></i>
                    </div>
                @endif
            </div>
            <h2 class="titulo-perfil">
                {{ $user->tipo_usuario == 'candidato' ? 'Mi Perfil de Candidato' : 'Perfil de Empresa' }}
            </h2>
        </header>

        <div class="contenedor-info-basica">
            <div class="dato-perfil">
                <i class="fa-solid fa-address-card"></i>
                <strong>Nombre:</strong>
                <span>{{ $perfil->nombre ?? $perfil->nombre_empresa }} {{ $perfil->apellidos ?? '' }}</span>
            </div>

            <div class="dato-perfil">
                <i class="fa-solid fa-envelope"></i>
                <strong>Correo:</strong>
                <span>{{ $user->email }}</span>
            </div>

            <div class="dato-perfil">
                <i class="fa-solid fa-location-dot"></i>
                <strong>Ubicación:</strong>
                <span>{{ $perfil->ubicacion ?? 'Gijón, Asturias' }}</span>
            </div>

            @if($user->tipo_usuario == 'candidato')
                {{-- SECCIÓN HABILIDADES --}}
                <div class="seccion-habilidades-perfil">
                    <h3 class="subtitulo-cv"><i class="fa-solid fa-brain"></i> Tus Aptitudes</h3>
                    <div class="contenedor-tags-habilidades">
                        @if(!empty($perfil->habilidades_clave))
                            @foreach(explode(',', $perfil->habilidades_clave) as $skill)
                                <span class="tag-habilidad">{{ trim($skill) }}</span>
                            @endforeach
                        @else
                            <p class="texto-informativo">No has definido tus habilidades aún. Pulsa en "Editar Perfil" para
                                añadirlas.</p>
                        @endif
                    </div>
                </div>

                {{-- SECCIÓN CV --}}
                <div class="seccion-cv">
                    <h3 class="subtitulo-cv"><i class="fa-solid fa-file-lines"></i> Tu Curriculum Vitae</h3>
                    <div class="estado-cv">
                        @if($perfil->curriculum)
                            <div class="cv-existente">
                                <a href="{{ asset('uploads/curriculums/' . $perfil->curriculum) }}" target="_blank"
                                    class="enlace-pdf">
                                    <i class="fa-solid fa-file-pdf"></i> Ver PDF actual
                                </a>
                                {{-- Aquí podrías añadir un formulario pequeño para borrarlo si quieres --}}
                            </div>
                        @else
                            <div class="alerta-sin-cv">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>No has subido ningún CV aún.</span>
                            </div>
                        @endif
                    </div>

                    {{-- Formulario de subida rápida --}}
                    <div class="formulario-subida-custom">
                        <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label for="curriculum" class="label-personalizado-cv">
                                <i class="fa-solid fa-file-arrow-up"></i> Seleccionar PDF
                            </label>
                            <input type="file" id="curriculum" name="curriculum" accept=".pdf" required style="display:none;"
                                onchange="document.getElementById('nombre-archivo-pdf').innerText = this.files[0].name">
                            <div id="nombre-archivo-pdf" class="nombre-archivo-status">Ningún archivo seleccionado</div>
                            <button type="submit" class="boton-subir-verde-perfil">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Actualizar CV
                            </button>
                        </form>
                    </div>
                </div>

                <div class="seccion-alertas-perfil">
                    <h3 class="subtitulo-cv"><i class="fa-solid fa-bell"></i> Tus Alertas</h3>
                    <p class="texto-informativo">Te avisaremos en el buscador cuando haya ofertas nuevas.</p>
                    <form action="{{ route('alertas.guardar') }}" method="POST" class="form-alertas-config">
                        @csrf
                        <select name="id_categoria" class="select-personalizado">
                            <option value="">Desactivar alertas</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}" {{ $alerta_actual == $cat->id_categoria ? 'selected' : '' }}>
                                    {{ $cat->nombre_categoria }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="boton-subir-verde-perfil">
                            Guardar Alerta
                        </button>
                    </form>
                </div>

            @else
                <div class="dato-perfil">
                    <i class="fa-solid fa-briefcase"></i>
                    <strong>Sector:</strong>
                    <span>{{ $perfil->sector ?? 'No definido' }}</span>
                </div>
            @endif
        </div>

        <div class="acciones-finales-perfil">
            <a href="{{ route('perfil.edit') }}" class="btn-editar">
                <i class="fa-solid fa-user-pen"></i> Editar Perfil
            </a>
            <a href="{{ route('logout') }}" class="enlace-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
            </a>
        </div>
    </section>
@endsection