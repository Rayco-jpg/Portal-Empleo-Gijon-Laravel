@extends('layouts.app')

@section('content')
<section class="seccion-crear-oferta">
    <header class="cabecera-formulario">
        <i class="fa-solid fa-file-circle-plus icono-cabecera-v"></i>
        <h2 class="titulo-pagina">Publicar Nueva Oferta de Empleo</h2>
        <p class="instrucciones">Complete los campos para publicar su vacante en Gijón.</p>
    </header>

    <div class="contenedor-formulario-oferta">
        <form action="{{ route('ofertas.store') }}" method="POST" class="form-estandar">
            @csrf

            <div class="grupo-entrada">
                <label for="titulo" class="label-formulario">Título de la vacante:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Ej: Camarero/a de sala" required class="input-texto" value="{{ old('titulo') }}">
            </div>

            <div class="grupo-entrada">
                <label for="descripcion" class="label-formulario">Descripción detallada:</label>
                <textarea id="descripcion" name="descripcion" rows="6" placeholder="Detalle las funciones y requisitos..." required class="input-textarea">{{ old('descripcion') }}</textarea>
            </div>

            <div class="fila-formulario">
                <div class="grupo-entrada col-medio">
                    <label for="id_categoria" class="label-formulario">Categoría profesional:</label>
                    <select id="id_categoria" name="id_categoria" required class="select-estandar">
                        <option value="" disabled selected>Seleccione una categoría...</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id_categoria }}">
                                {{ $cat->nombre_categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grupo-entrada col-medio">
                    <label for="zona_gijon" class="label-formulario">Zona de Gijón:</label>
                    <select id="zona_gijon" name="zona_gijon" class="select-estandar">
                        @foreach(['Centro', 'El Llano', 'La Arena', 'Natahoyo', 'Pumarin', 'Viesques', 'Somió'] as $zona)
                            <option value="{{ $zona }}">{{ $zona }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grupo-entrada">
                <label class="label-formulario">Ubicación exacta (Haz clic en el mapa):</label>
                <p class="instrucciones-mapa"><small>Marca el lugar donde se encuentra el puesto de trabajo.</small></p>
                
                <div id="mapa-seleccion"></div>
                
                <input type="hidden" name="latitud" id="lat_input" value="{{ old('latitud') }}">
                <input type="hidden" name="longitud" id="lng_input" value="{{ old('longitud') }}">
            </div>

            <div class="fila-formulario">
                <div class="grupo-entrada col-tercio">
                    <label for="salario" class="label-formulario">Salario (Aprox.):</label>
                    <input type="text" id="salario" name="salario" placeholder="Ej: 1200€ - 1500€" class="input-texto" value="{{ old('salario') }}">
                </div>

                <div class="grupo-entrada col-tercio">
                    <label for="jornada" class="label-formulario">Tipo de Jornada:</label>
                    <select id="jornada" name="jornada" class="select-estandar">
                        <option value="Completa">Completa</option>
                        <option value="Parcial">Parcial</option>
                        <option value="Intensiva">Intensiva</option>
                    </select>
                </div>

                <div class="grupo-entrada col-tercio">
                    <label for="experiencia" class="label-formulario">Experiencia mínima:</label>
                    <select id="experiencia" name="experiencia" class="select-estandar">
                        <option value="Sin experiencia">Sin experiencia</option>
                        <option value="1 año">1 año</option>
                        <option value="2-3 años">2 a 3 años</option>
                        <option value="Más de 5 años">Más de 5 años</option>
                    </select>
                </div>
            </div>

            <div class="acciones-formulario">
                <button type="submit" class="boton-publicar">
                    <i class="fa-solid fa-circle-check"></i> Publicar Oferta Ahora
                </button>
                <a href="{{ route('ofertas.index') }}" class="boton-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</section>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/mapa_oferta.js') }}"></script>
@endsection