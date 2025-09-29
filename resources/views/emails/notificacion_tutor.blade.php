<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $asunto }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2e5382 0%, #1e3a5f 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            color: #2e5382;
            margin-bottom: 20px;
        }
        .message-box {
            background: #f8f9fa;
            border-left: 4px solid #64d423;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #2e5382;
            min-width: 120px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #2e5382;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Notificación de la Institución Educativa</h1>
        </div>
        
        <div class="content">
            <p class="greeting">Estimado/a {{ $nombreTutor }},</p>
            
            <p>Reciba un cordial saludo de nuestra institución educativa. Nos comunicamos con usted en relación al estudiante <strong>{{ $nombreEstudiante }}</strong>.</p>
            
            <div class="info-row">
                <span class="info-label">Asunto:</span>
                <span>{{ $asunto }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Estudiante:</span>
                <span>{{ $nombreEstudiante }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Fecha:</span>
                <span>{{ $fecha }}</span>
            </div>
            
            <div class="message-box">
                <h3 style="margin-top: 0; color: #2e5382;">Mensaje:</h3>
                <p style="white-space: pre-wrap;">{{ $mensaje }}</p>
            </div>
            
            <p>Si tiene alguna consulta o necesita información adicional, no dude en contactarnos.</p>
            
            <p>Atentamente,<br>
            <strong>Institución Educativa</strong></p>
        </div>
        
        <div class="footer">
            <p><strong>Este es un correo automático, por favor no responder.</strong></p>
            <p>© {{ date('Y') }} Institución Educativa. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>