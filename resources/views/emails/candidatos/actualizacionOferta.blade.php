<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        .wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .content {
            padding: 40px 30px;
            line-height: 1.8;
        }

        .content p {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .info-card {
            background-color: #f1f5f9;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            border-left: 6px solid #007bff;
        }

        .info-card p {
            margin: 8px 0;
            font-size: 15px;
            color: #475569;
        }

        .badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #ffffff;
        }

        .btn {
            display: block;
            width: 220px;
            margin: 30px auto 10px;
            padding: 15px;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }

        .footer {
            text-align: center;
            padding: 30px;
            font-size: 13px;
            color: #64748b;
            background-color: #f8fafc;
        }

        .footer hr {
            border: 0;
            border-top: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <header class="header">
                <h1>GijónEmpleo</h1>
            </header>

            <main class="content">
                <p>Hola, <strong>{{ $usuario->name }}</strong></p>

                <p>Te informamos que se han producido cambios en tu candidatura. El estado del proceso de selección ha sido actualizado:</p>

                <div class="info-card">
                    <p><strong>Puesto:</strong> {{ $oferta->titulo }}</p>
                    <p><strong>Estado Actual:</strong> 
                        <span class="badge" style="background-color: {{ 
                            match(strtolower($nuevoEstado)) {
                                'aceptado', 'contratado' => '#22c55e', 
                                'rechazado', 'descartado' => '#ef4444',
                                'finalista' => '#8e44ad',
                                'en revisión', 'revision' => '#f59e0b',
                                default => '#007bff'
                            } 
                        }};">
                            {{ $nuevoEstado }}
                        </span>
                    </p>
                </div>

                <p>Para conocer los detalles de la oferta o realizar el seguimiento de tus otras inscripciones, haz clic en el siguiente botón:</p>
                
                <a href="{{ url('/login') }}" class="btn">Ver mi Candidatura</a>
            </main>

            <footer class="footer">
                <hr>
                <p>Atentamente,<br><strong>El equipo de GijónEmpleo</strong></p>
                <p style="margin-top: 20px;">
                    <small>Este es un correo informativo generado automáticamente.<br>
                    Por favor, no respondas directamente a este mensaje.</small>
                </p>
            </footer>
        </div>
    </div>
</body>
</html>