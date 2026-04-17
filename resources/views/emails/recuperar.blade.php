<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h1 style="color: #0056b3;">Portal de Empleo Gijón</h1>
        <p>Hola,</p>
        <p>Has solicitado restablecer la contraseña de tu cuenta en nuestro portal.</p>
        <p>Haz clic en el botón de abajo para elegir una nueva clave (el enlace caduca en 1 hora):</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" 
               style="background-color: #0056b3; 
                      color: #ffffff; 
                      padding: 12px 25px; 
                      text-decoration: none; 
                      border-radius: 5px; 
                      font-weight: bold; 
                      display: inline-block;">
                Restablecer Contraseña
            </a>
        </div>

        <p>Si no puedes ver el botón, copia y pega este enlace en tu navegador:</p>
        <p style="word-break: break-all; color: #0056b3;">{{ $url }}</p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #777;">Si no has solicitado este cambio, puedes ignorar este correo de forma segura.</p>
    </div>
</body>
</html>