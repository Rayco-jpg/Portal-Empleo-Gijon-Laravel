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
                        <img src="{{ asset('uploads/perfiles/' . $perfil->foto) }}?v={{ time() }}" id="img-preview"
                            class="foto-redonda-edit">
                    @else
                        <div id="img-placeholder" class="avatar-vacio-edit">
                            <i class="fa-solid {{ $user->tipo_usuario == 'candidato' ? 'fa-user-tie' : 'fa-building' }} fa-3x"></i>
                        </div>
                        <img src="" id="img-preview" class="foto-redonda-edit" style="display:none;">
                    @endif
                </div>

                <label for="foto" class="btn-cambiar-foto">
                    <i class="fa-solid fa-camera"></i> Seleccionar nueva foto
                </label>
                <input type="file" id="foto" name="foto" accept="image/*" style="display:none;">
            </div>

            <div class="grupo-input">
                <label for="nuevo_nombre">Nombre:</label>
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
            @endif

            <div class="grupo-input">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" value="{{ old('ubicacion', $perfil->ubicacion) }}">
            </div>

            @if($user->tipo_usuario == 'candidato')
                <div class="grupo-input">
                    <label for="disponible"><i class="fa-solid fa-clock"></i> ¿Estás disponible para trabajar?</label>
                    <select name="disponible" id="disponible" class="control-formulario">
                        <option value="1" {{ old('disponible', $perfil->disponible ?? 1) == 1 ? 'selected' : '' }}>
                            Sí, estoy disponible
                        </option>
                        <option value="0" {{ old('disponible', $perfil->disponible ?? 1) == 0 ? 'selected' : '' }}>
                            No, no busco trabajo ahora
                        </option>
                    </select>
                    <p class="ayuda-input">Esto aparecerá en tu perfil público para las empresas.</p>
                </div>
                
                <div class="grupo-input">
                    <label for="biografia">Sobre mí (Descripción profesional):</label>
                    <textarea id="biografia" name="biografia"
                        placeholder="Cuéntale a las empresas quién eres, tu experiencia y qué buscas..."
                        rows="5">{{ old('biografia', $perfil->biografia) }}</textarea>
                    <p class="ayuda-input">Una buena descripción aumenta tus posibilidades de ser contratado.</p>
                </div>

                <div class="grupo-input">
                    <label for="habilidades_clave">Mis Habilidades y Aptitudes:</label>
                    <textarea id="habilidades_clave" name="habilidades_clave"
                        placeholder="Ejemplo: camarero, atención al cliente, inglés, pda..."
                        rows="3">{{ old('habilidades_clave', $perfil->habilidades_clave) }}</textarea>
                    <p class="ayuda-input">Escribe tus habilidades separadas por comas.</p>
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