@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
    <section class="seccion-editar-perfil contenedor-editar-perfil shadow-lg">
        <h2 class="titulo-editar"><i class="fa-solid fa-user-pen"></i> Editar mis datos de perfil</h2>

        <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data" class="form-edicion">
            @csrf
            @method('PUT')

            <div class="grupo-entrada centro-foto">
                <div class="contenedor-preview">
                    @if($perfil->foto)
                        @php
                            $fotoPath = asset('uploads/perfiles/' . $perfil->foto);
                        @endphp
                        <img src="{{ $fotoPath }}?v={{ time() }}" id="img-preview" class="foto-redonda-edit">
                    @else
                        <div id="img-placeholder" class="avatar-vacio-edit">
                            <i
                                class="fa-solid {{ $user->tipo_usuario == 'candidato' ? 'fa-user-tie' : 'fa-building' }} fa-3x"></i>
                        </div>
                        <img src="" id="img-preview" class="foto-redonda-edit" style="display:none;">
                    @endif
                </div>

                <label for="foto" class="btn-cambiar-foto">
                    <i class="fa-solid fa-camera"></i> Seleccionar
                    {{ $user->tipo_usuario == 'candidato' ? 'nueva foto' : 'nuevo logo' }}
                </label>
                <input type="file" id="foto" name="foto" accept="image/*" style="display:none;">
            </div>

            <div class="grupo-input">
                <label for="nuevo_nombre">Nombre {{ $user->tipo_usuario == 'empresa' ? 'de la Empresa' : '' }}:</label>
                <input type="text" id="nuevo_nombre" name="nuevo_nombre"
                    value="{{ old('nuevo_nombre', $perfil->nombre ?? $perfil->nombre_empresa) }}" required>
            </div>

            @if($user->tipo_usuario == 'candidato')
                <div class="grupo-input">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $perfil->apellidos) }}"
                        required>
                </div>
            @else
                <div class="grupo-input">
                    <label for="sector">Sector Profesional:</label>
                    <input type="text" id="sector" name="sector" value="{{ old('sector', $perfil->sector) }}">
                </div>

                <div class="grupo-input">
                    <label for="tamano">Tamaño de la empresa:</label>
                    <select name="tamano" id="tamano" class="form-control">
                        <option value="Pequeña" {{ old('tamano', $perfil->tamano) == 'Pequeña' ? 'selected' : '' }}>Pequeña (1-10
                            empleados)</option>
                        <option value="Mediana" {{ old('tamano', $perfil->tamano) == 'Mediana' ? 'selected' : '' }}>Mediana (11-50
                            empleados)</option>
                        <option value="Grande" {{ old('tamano', $perfil->tamano) == 'Grande' ? 'selected' : '' }}>Grande (+50
                            empleados)</option>
                    </select>
                </div>
            @endif

            <div class="grupo-input">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" value="{{ old('ubicacion', $perfil->ubicacion) }}">
            </div>

            @if($user->tipo_usuario == 'candidato')
                <div class="grupo-input">
                    <label for="disponible"><i class="fa-solid fa-clock"></i> ¿Estás disponible para trabajar?</label>
                    <select name="disponible" id="disponible" class="control-formulario">
                        <option value="1" {{ old('disponible', $perfil->disponible ?? 1) == 1 ? 'selected' : '' }}>Sí, estoy
                            disponible</option>
                        <option value="0" {{ old('disponible', $perfil->disponible ?? 1) == 0 ? 'selected' : '' }}>No, no busco
                            trabajo ahora</option>
                    </select>
                </div>

                <div class="grupo-input">
                    <label for="biografia">Sobre mí (Descripción profesional):</label>
                    <textarea id="biografia" name="biografia" rows="5">{{ old('biografia', $perfil->biografia) }}</textarea>
                </div>


                <div class="grupo-input">
                    <label for="habilidades_clave"><i class="fa-solid fa-tags"></i> Tus Aptitudes:</label>
                    <input type="text" id="habilidades_clave" name="habilidades_clave"
                        value="{{ old('habilidades_clave', $perfil->habilidades_clave) }}"
                        placeholder="Ej: camarero, servicial...">
                </div>
            @else
                <div class="grupo-input">
                    <label for="descripcion">Sobre nosotros (Descripción de la empresa):</label>
                    <textarea id="descripcion" name="descripcion" rows="5"
                        placeholder="Cuenta la historia y valores de tu empresa...">{{ old('descripcion', $perfil->descripcion) }}</textarea>
                </div>

                <div class="grupo-input">
                    <label for="sitio_web"><i class="fa-solid fa-globe"></i> Sitio Web:</label>
                    <input type="url" id="sitio_web" name="sitio_web" value="{{ old('sitio_web', $perfil->sitio_web) }}"
                        placeholder="https://www.tuempresa.com">
                </div>

                <div class="grupo-input">
                    <label for="twitter"><i class="fa-brands fa-x-twitter"></i> Twitter / X:</label>
                    <input type="text" id="twitter" name="twitter" value="{{ old('twitter', $perfil->twitter) }}"
                        placeholder="@empresa">
                </div>
                <div class="grupo-input">
                    <label><i class="fa-brands fa-facebook"></i> Facebook (URL):</label>
                    <input type="text" name="facebook" value="{{ old('facebook', $perfil->facebook) }}"
                        placeholder="https://facebook.com/tuempresa">
                </div>

                <div class="grupo-input">
                    <label><i class="fa-brands fa-instagram"></i> Instagram (URL):</label>
                    <input type="text" name="instagram" value="{{ old('instagram', $perfil->instagram) }}"
                        placeholder="https://instagram.com/tuempresa">
                </div>

                <div class="grupo-input">
                    <label><i class="fa-brands fa-whatsapp"></i> WhatsApp (Número):</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $perfil->whatsapp) }}"
                        placeholder="Ej: 34600000000">
                </div>
            @endif

            <div class="botones-form">
                <button type="submit" class="btn-guardar">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                </button>
                <a href="{{ route('perfil') }}" class="btn-cancelar">
                    <i class="fa-solid fa-xmark"></i> Cancelar
                </a>
            </div>
        </form>
    </section>
@endsection