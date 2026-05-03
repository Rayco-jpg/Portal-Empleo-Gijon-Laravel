<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .envoltorio {
            width: 100%;
            padding: 40px 0;
            background-color: #f1f5f9;
        }
        .contenedor {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .cabecera {
            background-color: #0f172a; 
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .cabecera h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .contenido {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .tarjeta-info {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .tarjeta-info h2 {
            margin-top: 0;
            font-size: 16px;
            color: #0f172a;
            border-bottom: 2px solid #3b82f6;
            display: inline-block;
            padding-bottom: 5px;
        }
        .boton {
            display: block;
            width: 220px;
            margin: 30px auto 0;
            padding: 15px;
            background-color: #3b82f6;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
        }
        .pie {
            text-align: center;
            padding: 25px;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="envoltorio">
        <div class="contenedor">
            <header class="cabecera">
                <h1>GijónEmpleo Empresas</h1>
            </header>

            <main class="contenido">
                <p>Estimados responsables de <strong>{{ $oferta->datosEmpresa->nombre_empresa }}</strong></p>
                
                <p>Os informamos de que un nuevo candidato se ha inscrito en vuestra oferta de empleo activa.</p>

                <div class="tarjeta-info">
                    <h2>Detalles del Candidato</h2>
                    <p><strong>Nombre:</strong> {{ $usuario->nombre }} {{ $usuario->apellidos }}</p>
                    <p><strong>Puesto:</strong> {{ $oferta->titulo }}</p>
                    <p><strong>Fecha de inscripción:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                </div>

                <p>Podéis revisar el perfil completo del candidato y gestionar su candidatura desde vuestro panel de control.</p>
                
                <a href="{{ url('/empresa/candidatos') }}" class="boton">Gestionar Candidaturas</a>
            </main>

            <footer class="pie">
                <p>© {{ date('Y') }} GijónEmpleo - Portal de Empresas</p>
                <p>Aviso: Este es un correo automático, por favor no responda a este mensaje.</p>
            </footer>
        </div>
    </div>
</body>
</html>