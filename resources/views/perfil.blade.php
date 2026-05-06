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
                <div class="dato-perfil">
                    <i class="fa-solid fa-calendar-check"></i>
                    <strong>Miembro desde:</strong>
                    <span>{{ $user->created_at ? $user->created_at->format('M Y') : date('M Y') }}</span>
                </div>
                @if($user->tipo_usuario == 'candidato')
                    <div class="dato-perfil">
                        <i
                            class="fa-solid fa-circle-dot {{ ($perfil->disponible ?? 1) == 1 ? 'text-success' : 'text-danger' }}"></i>
                        <strong>Estado:</strong>
                        <span class="badge-estado {{ ($perfil->disponible ?? 1) == 1 ? 'bg-success' : 'bg-danger' }}">
                            {{ ($perfil->disponible ?? 1) == 1 ? 'Disponible para trabajar' : 'No disponible' }}
                        </span>
                    </div>
                @endif
                <div class="seccion-perfil-bloque">
                    <h3 class="subtitulo-cv"><i class="fa-solid fa-user-tag"></i> Sobre mí</h3>
                    <div class="cuadro-biografia">
                        @if(!empty($perfil->biografia))
                            <p class="texto-biografia">{{ $perfil->biografia }}</p>
                        @else
                            <p class="texto-informativo">
                                <i class="fa-solid fa-circle-info"></i>
                                Aún no has escrito una descripción profesional. Pulsa en "Editar Perfil" para presentarte a las
                                empresas.
                            </p>
                        @endif
                    </div>
                </div>
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

                <div class="seccion-cv">
                    <h3 class="subtitulo-cv"><i class="fa-solid fa-file-lines"></i> Tu Curriculum Vitae</h3>
                    <div class="estado-cv">
                        @if($perfil->curriculum)
                            <div class="cv-existente">
                                <a href="{{ asset('uploads/curriculums/' . $perfil->curriculum) }}" target="_blank"
                                    class="enlace-pdf">
                                    <i class="fa-solid fa-file-pdf"></i> Ver PDF actual
                                </a>
                            </div>
                        @else
                            <div class="alerta-sin-cv">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>No has subido ningún CV aún.</span>
                            </div>
                        @endif
                    </div>

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

                <div class="dato-perfil">
                    <i class="fa-solid fa-users-viewfinder"></i>
                    <strong>Tamaño:</strong>
                    <span>
                        {{ $perfil->tamano ?? 'No especificado' }}
                        @if($perfil->tamano == 'Pequeña')
                            (1-10 empleados)
                        @elseif($perfil->tamano == 'Mediana')
                            (11-50 empleados)
                        @elseif($perfil->tamano == 'Grande')
                            (+50 empleados)
                        @endif
                    </span>
                </div>

                <div class="contenedor-redes-sociales">
                    <strong>Redes Sociales:</strong>
                    <div class="iconos-fila">
                        @if($perfil->twitter)
                            <a href="{{ $perfil->twitter }}" target="_blank" class="icono-red-social twitter">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                        @endif

                        @if($perfil->facebook)
                            <a href="{{ $perfil->facebook }}" target="_blank" class="icono-red-social facebook">
                                <i class="fa-brands fa-facebook"></i>
                            </a>
                        @endif

                        @if($perfil->instagram)
                            <a href="{{ $perfil->instagram }}" target="_blank" class="icono-red-social instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        @endif

                        @if($perfil->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $perfil->whatsapp) }}" target="_blank"
                                class="icono-red-social whatsapp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="seccion-perfil-bloque" style="width: 100%; margin-top: 20px;">
                    <h3 class="subtitulo-cv"><i class="fa-solid fa-building-user"></i> Sobre nosotros</h3>
                    <div class="cuadro-biografia">
                        @if(!empty($perfil->descripcion))
                            <p class="texto-biografia">{{ $perfil->descripcion }}</p>
                        @else
                            <p class="texto-informativo">
                                <i class="fa-solid fa-circle-info"></i>
                                Esta empresa aún no ha añadido una descripción.
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        @if(Auth::user()->es_premium)
            <div class="seccion-perfil-bloque tarjeta-premium">
                <div class="cabecera-premium">
                    <i class="fa-solid fa-crown"></i> Gestión de Suscripción Premium
                </div>

                <p class="texto-info-premium">Tu cuenta tiene acceso a todas las ventajas exclusivas.</p>

                <p class="fecha-validez">
                    <strong>Válido hasta:</strong>
                    {{ \Carbon\Carbon::parse(Auth::user()->premium_hasta)->format('d/m/Y') }}
                </p>

                <div class="acciones-premium">
                    <a href="{{ route('premium.facturacion') }}" class="btn-premium-action btn-facturas">
                        <i class="fa-solid fa-file-invoice"></i> Mis Facturas
                    </a>

                    <form action="{{ route('premium.cancelar') }}" method="POST" class="form-cancelar">
                        @csrf
                        <button type="submit" class="btn-premium-action btn-cancelar-premium">
                            <i class="fa-solid fa-xmark"></i> Cancelar Suscripción
                        </button>
                    </form>
                </div>
            </div>
        @endif
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