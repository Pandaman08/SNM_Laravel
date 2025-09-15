<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Solicitud</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            color: #2e5382;
        }
        .status-approved {
            color: #10B981;
            font-size: 24px;
            font-weight: bold;
        }
        .status-rejected {
            color: #EF4444;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            margin: 20px 0;
            line-height: 1.6;
            color: #333;
        }
        .reason-box {
            background-color: #FEF2F2;
            border-left: 4px solid #EF4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .credentials-box {
            background-color: #F0F9FF;
            border-left: 4px solid #10B981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            color: #666;
        }
        .btn {
            display: inline-block;
            background-color: #2e5382;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Colegio Bruning</h1>
            @if($status === 'approved')
                <p class="status-approved">¡Solicitud Aprobada!</p>
            @else
                <p class="status-rejected">Solicitud Rechazada</p>
            @endif
        </div>

        <div class="content">
            <p>Estimado/a {{ $user->persona->name }} {{ $user->persona->lastname }},</p>
            
            @if($status === 'approved')
                <p>Nos complace informarle que su solicitud de registro como tutor ha sido <strong>aprobada</strong>.</p>
                
                <div class="credentials-box">
                    <h3>Sus datos de acceso:</h3>
                    <ul>
                        <li><strong>Email:</strong> {{ $user->email }}</li>
                        <li><strong>Contraseña:</strong> La que registró durante el proceso</li>
                    </ul>
                </div>

                <p>Ya puede acceder al sistema haciendo clic en el siguiente enlace:</p>
                <div style="text-align: center;">
                    <a href="{{ route('login') }}" class="btn">Iniciar Sesión</a>
                </div>
            @else
                <p>Lamentamos informarle que su solicitud de registro como tutor ha sido <strong>rechazada</strong>.</p>
                @if($reason)
                    <div class="reason-box">
                        <strong>Motivo del rechazo:</strong><br>
                        {{ $reason }}
                    </div>
                @endif
                <p>Si considera que esto es un error o desea más información, puede contactarnos al teléfono del colegio.</p>
            @endif

            <p>Gracias por su interés en formar parte de nuestra comunidad educativa.</p>
        </div>

        <div class="footer">
            <p><strong>Atentamente,</strong><br>
            Administración del Colegio Bruning</p>
        </div>
    </div>
</body>
</html>