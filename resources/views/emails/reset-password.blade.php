<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5;">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="padding: 20px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <!-- Logo y Título -->
                <tr>
                    <td align="center" style="padding: 30px 20px 20px;">
                        <!-- Cambiar por el logo -->
                        <div style="width: 60px; height: 60px; background-color: #222; border-radius: 50%; display: inline-block; position: relative;">
                            <div style="position: absolute; top: 15px; left: 15px; width: 30px; height: 30px; border: 4px solid #fff; border-radius: 50%;"></div>
                        </div>
                        <h1 style="margin: 20px 0 0; font-size: 24px; font-weight: bold; color: #222;">Recuperación de
                            Contraseña</h1>
                    </td>
                </tr>

                <!-- Separador -->
                <tr>
                    <td style="padding: 0 20px;">
                        <hr style="border: none; height: 1px; background-color: #e5e5e5; margin: 0;">
                    </td>
                </tr>

                <!-- Contenido -->
                <tr>
                    <td style="padding: 20px;">
                        <p style="margin-top: 0;">Hola {{ $name }},</p>
                        <p style="margin-bottom: 25px;">Has solicitado restablecer tu contraseña. Haz clic en el
                            siguiente enlace para crear una nueva contraseña:</p>

                        <!-- Botón -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center">
                                    <a href="{{ route("admin.restore.password.token", ['token' => $token]) }}"
                                       style="display: inline-block; background-color: #4f46e5; color: white; text-decoration: none; padding: 12px 0; border-radius: 4px; font-weight: 500; text-align: center; width: 100%; font-size: 16px;">Restablecer
                                        Contraseña</a>
                                </td>
                            </tr>
                        </table>

                        <!-- Mensaje de expiración -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 20px;">
                            <tr>
                                <td style="background-color: #0dcaf0; padding: 12px; border-radius: 4px;">
                                    <div style="display: flex; align-items: center;">
                                        <img src="{{ asset('assets/info-icon.png') }}"
                                             alt="info" width="20" height="20">
                                        <p style="margin: 0; color: #333; padding-left: 10px;">Este enlace expirará en
                                            60 minutos</p>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- Advertencia -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 15px;">
                            <tr>
                                <td style="background-color: #ffc107; padding: 12px; border-radius: 4px;">
                                    <div style="display: flex; align-items: center;">
                                        <img src="{{  asset('assets/warning-icon.png') }}" alt="warning"
                                             width="20" height="20">
                                        <p style="margin: 0; color: #333; padding-left: 10px;">Si no has solicitado un
                                            cambio de contraseña,
                                            puedes ignorar este correo.</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Separador -->
                <tr>
                    <td style="padding: 0 20px;">
                        <hr style="border: none; height: 1px; background-color: #e5e5e5; margin: 0;">
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding: 20px;">
                        <p style="margin-top: 0; margin-bottom: 4px;">Saludos,</p>
                        <p style="margin-top: 0; font-weight: bold;">El equipo de administración</p>

                        <p style="font-size: 12px; color: #666; margin: 20px 0 0; text-align: center;">Este es un correo
                            automático, por favor no responda a este mensaje.</p>
                        <p style="font-size: 12px; color: #666; margin: 5px 0 0; text-align: center;">
                            &copy; {{ date('Y') }} {{ env('APP_Name') }}. Todos los derechos reservados.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>