@extends('layouts.app')

@section('title', 'Contacto y Soporte')

@section('content')
<div class="contenedor-contacto">
    <section class="seccion-cabecera-contacto">
        <h2>Contacta con nosotros</h2>
        <p>¿Has encontrado algún error en el sistema o quieres hacernos una sugerencia?</p>
    </section>

    <div class="tarjeta-formulario-contacto">
        <form action="#" method="POST">
            @csrf
            <div class="grupo-formulario">
                <label for="asunto">¿En qué podemos ayudarte?</label>
                <select name="asunto" id="asunto">
                    <option value="error">Reportar un error técnico</option>
                    <option value="oferta">Problema con una oferta de trabajo</option>
                    <option value="cuenta">Dudas sobre mi cuenta</option>
                    <option value="otro">Otros motivos</option>
                </select>
            </div>

            <div class="grupo-formulario">
                <label for="mensaje">Detalles del mensaje</label>
                <textarea name="mensaje" id="mensaje" rows="6" placeholder="Explica detalladamente lo sucedido..."></textarea>
            </div>

            <button type="submit" class="boton-enviar-contacto">
                <i class="fa-solid fa-paper-plane"></i> Enviar reporte
            </button>
        </form>
    </div>
</div>
@endsection