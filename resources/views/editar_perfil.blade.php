@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<section class="seccion-editar-perfil contenedor-editar-perfil shadow-lg">
    <h2 class="titulo-editar"><i class="fa-solid fa-user-pen"></i> Editar mis datos de perfil</h2>

    <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data" class="form-edicion">
        @csrf
        @method('PUT')

        {{-- GESTIÓN DE FOTO --}}
        <div class="grupo-entrada centro-foto">
            <div class="contenedor-preview">
                @if($perfil->foto)
                    <img src="{{ asset('uploads/perfiles/' . $perfil->foto) }}?v={{ time() }}" id="img-preview" class="foto-redonda-edit">
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
            <input type="file" id="foto" name="foto" accept="image/*" style="display:none;" onchange="previewImage(this)">
        </div>

        {{-- NOMBRE (Común para ambos) --}}
        <div class="grupo-input">
            <label for="nuevo_nombre">Nombre:</label>
            <input type="text" id="nuevo_nombre" name="nuevo_nombre"
                value="{{ old('nuevo_nombre', $perfil->nombre ?? $perfil->nombre_empresa) }}" required>
        </div>

        @if($user->tipo_usuario == 'candidato')
            {{-- APELLIDOS --}}
            <div class="grupo-input">
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos"
                    value="{{ old('apellidos', $perfil->apellidos) }}" required>
            </div>
        @else
            {{-- SECTOR (Para Empresas) --}}
            <div class="grupo-input">
                <label for="sector">Sector Profesional:</label>
                <input type="text" id="sector" name="sector"
                    value="{{ old('sector', $perfil->sector) }}">
            </div>
        @endif

        {{-- UBICACIÓN --}}
        <div class="grupo-input">
            <label for="ubicacion">Ubicación:</label>
            <input type="text" id="ubicacion" name="ubicacion"
                value="{{ old('ubicacion', $perfil->ubicacion) }}">
        </div>

        @if($user->tipo_usuario == 'candidato')
            {{-- HABILIDADES --}}
            <div class="grupo-input">
                <label for="habilidades_clave">Mis Habilidades y Aptitudes:</label>
                <textarea id="habilidades_clave" name="habilidades_clave" 
                    placeholder="Ejemplo: camarero, atención al cliente, inglés, pda..."
                    rows="3">{{ old('habilidades_clave', $perfil->habilidades_clave) }}</textarea>
                <p class="ayuda-input">Escribe tus habilidades separadas por comas. Esto ayudará a la IA a calcular tu afinidad con las ofertas.</p>
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

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('img-preview');
            const placeholder = document.getElementById('img-placeholder');
            
            preview.src = e.target.result;
            preview.style.display = 'block';
            if(placeholder) placeholder.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection