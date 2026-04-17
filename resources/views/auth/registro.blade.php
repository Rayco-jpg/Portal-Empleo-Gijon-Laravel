<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Portal Empleo Gijon</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/main.js') }}" defer></script>
</head>

<body class="cuerpo-acceso">
    <main> 
        <article class="tarjeta-acceso">
            <header class="cabecera-acceso">
                <img src="{{ asset('assets/imagenes/logo_portal_empleo.png') }}" alt="Logo Portal Empleo" class="logo-acceso">
                <h1>Crear Cuenta</h1>
                <p>Únete al portal de empleo de tu ciudad</p>
            </header>

            {{-- Mensajes de éxito o error estilo Laravel --}}
            @if(session('exito'))
                <section class="alerta alerta-exito" role="alert">
                    <p>{{ session('exito') }} <a href="{{ route('login') }}">Ir al Login</a></p>
                </section>
            @endif

            @if(session('error'))
                <section class="alerta alerta-error" role="alert">
                    <p>{{ session('error') }}</p>
                </section>
            @endif

            {{-- El formulario apunta a la ruta de Laravel --}}
            <form action="{{ route('registrar') }}" method="POST" enctype="multipart/form-data" class="formulario-estandar">
                @csrf

                <section class="grupo-input">
                    <label for="email">Email:</label>
                    <div class="input-con-icono">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="input-estandar" placeholder="correo@ejemplo.com">
                    </div>
                </section>

                <section class="grupo-input">
                    <label for="password">Contraseña:</label>
                    <div class="input-con-icono">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" required minlength="8" class="input-estandar" placeholder="••••••••">
                    </div>
                </section>

                <section class="grupo-input">
                    <label for="tipo_usuario">¿Qué perfil buscas?</label>
                    <select name="tipo_usuario" id="tipo_usuario" class="input-estandar">
                        <option value="candidato" {{ old('tipo_usuario') == 'candidato' ? 'selected' : '' }}>Soy Candidato (busco empleo)</option>
                        <option value="empresa" {{ old('tipo_usuario') == 'empresa' ? 'selected' : '' }}>Soy Empresa (ofrezco empleo)</option>
                    </select>
                </section>

                {{-- Sección Candidato --}}
                <div id="seccion_candidato">
                    <section class="grupo-input">
                        <label for="nombre_candidato">Nombre:</label>
                        <input type="text" id="nombre_candidato" name="nombre_candidato" value="{{ old('nombre_candidato') }}" class="input-estandar">
                    </section>
                    <section class="grupo-input">
                        <label for="apellidos">Apellidos:</label>
                        <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" class="input-estandar">
                    </section>
                    
                    <section class="grupo-input">
                        <span class="label-falsa">CV (PDF):</span>
                        <input type="file" name="cv" id="cv" accept=".pdf" class="input-file-oculto">
                        <label for="cv" class="boton-file-diseno">
                            <i class="fa-solid fa-file-pdf fa-2x"></i>
                            <span id="texto-archivo">Seleccionar currículum (PDF)</span>
                        </label>
                    </section>
                </div>

                {{-- Sección Empresa --}}
                <div id="seccion_empresa" style="display: none;">
                    <section class="grupo-input">
                        <label for="nombre_empresa">Nombre de la Empresa:</label>
                        <input type="text" id="nombre_empresa" name="nombre_empresa" value="{{ old('nombre_empresa') }}" class="input-estandar">
                    </section>
                    <section class="grupo-input">
                        <label for="sector">Sector:</label>
                        <input type="text" id="sector" name="sector" value="{{ old('sector') }}" class="input-estandar">
                    </section>
                </div>

                <button type="submit" class="boton-acceso">Finalizar Registro</button>
            </form>

            <footer class="pie-acceso">
                <p>¿Ya tienes cuenta? <a href="{{ route('login') }}" class="enlace-secundario">Inicia sesión aquí</a></p>
            </footer>
        </article>
    </main>
</body>
</html>