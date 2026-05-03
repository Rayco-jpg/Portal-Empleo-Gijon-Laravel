@extends('layouts.app')

@section('content')
    <div class="contenedor">
        <div class="fila">
            <div class="col-md-12">
                <div class="tarjeta">
                    <div class="tarjeta-cabecera">
                        <div class="informacion-cabecera">
                            <h4 class="titulo">Gestión Global de Candidatos</h4>
                            <small class="subtitulo">Inscripciones totales de todas tus ofertas</small>
                        </div>
                        <div class="acciones-cabecera">
                            <a href="{{ route('ofertas.index') }}" class="boton-volver">
                                <i class="fas fa-arrow-left"></i> Volver a mis ofertas
                            </a>
                        </div>
                    </div>

                    <div class="tarjeta-cuerpo">
                        @if($todosInscritos->isEmpty())
                            <div class="estado-vacio">
                                <i class="fas fa-users-slash"></i>
                                <p>Aún no tienes candidatos inscritos en ninguna oferta.</p>
                            </div>
                        @else
                            <div class="contenedor-tabla">
                                <table class="tabla-personalizada">
                                    <thead>
                                        <tr>
                                            <th>Candidato</th>
                                            <th>Oferta Relacionada</th>
                                            <th>Fecha Inscripción</th>
                                            <th>Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todosInscritos as $inscripcion)
                                            <tr>
                                                <td data-label="Candidato">
                                                    <div class="info-usuario">
                                                        <div class="avatar-usuario">
                                                            <div class="avatar-inicial">
                                                                {{ strtoupper(substr($inscripcion->candidato->nombre ?? 'C', 0, 1)) }}
                                                            </div>
                                                        </div>

                                                        <div class="detalles-usuario">
                                                            <span class="nombre-usuario">
                                                                {{ $inscripcion->candidato->nombre ?? 'Sin nombre' }}
                                                                {{ $inscripcion->candidato->apellidos ?? '' }}
                                                            </span>
                                                            <small class="email-usuario">
                                                                <i class="far fa-envelope"></i>
                                                                {{ $inscripcion->candidato->usuario->email ?? 'Sin email' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td data-label="Oferta">
                                                    <div class="info-oferta">
                                                        <span class="titulo-oferta">
                                                            {{ $inscripcion->oferta->titulo ?? 'Oferta eliminada' }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td data-label="Fecha" class="info-fecha">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ $inscripcion->created_at ? $inscripcion->created_at->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td data-label="Estado">
                                                    <span class="etiqueta-estado estado-{{ strtolower($inscripcion->estado ?? 'pendiente') }}">
                                                        {{ ucfirst($inscripcion->estado ?? 'Pendiente') }}
                                                    </span>
                                                </td>
                                                <td data-label="Acciones" class="text-center">
                                                    <a href="{{ route('perfil.candidato', $inscripcion->id_candidato) }}"
                                                       class="boton-ver-perfil">
                                                        <i class="fas fa-eye"></i> Ver Perfil
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection