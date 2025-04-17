<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .button {
            display: inline-block;
            background-color: #3490dc;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin: 20px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Recuperación de Contraseña</h1>

    <p>Hola {{ $name }},</p>

    <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para crear una nueva contraseña:</p>

    <a href="{{ route("admin.restore.password.token", ['token' => $token]) }}" class="button">
        Restablecer Contraseña
    </a>

    <p>Este enlace expirará en 60 minutos.</p>

    <p>Si no has solicitado un cambio de contraseña, puedes ignorar este correo.</p>

    <p>Saludos,<br>El equipo de administración</p>

    <div class="footer">
        <p>Este es un correo automático, por favor no responda a este mensaje.</p>
    </div>
</div>
</body>
</html>