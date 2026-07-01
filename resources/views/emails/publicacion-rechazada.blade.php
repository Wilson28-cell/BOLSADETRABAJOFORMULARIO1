<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicación Rechazada</title>
</head>
<body style="font-family:Arial, sans-serif; color:#333;">
    <div style="max-width:600px; margin:0 auto; padding:20px; background:#f8f9fa; border:1px solid #e9ecef; border-radius:8px;">
        <h1 style="font-size:24px; margin-bottom:16px;">Publicación rechazada</h1>
        <p>Hola {{ $data['nombre_empresa'] ?? 'Usuario' }},</p>
        <p>Tu publicación de <strong>{{ $data['tipo'] === 'servicio' ? 'servicio' : 'producto' }}</strong> <strong>{{ $data['titulo'] }}</strong> ha sido rechazada.</p>
        @if(!empty($data['motivo']))
            <p><strong>Motivo:</strong> {{ $data['motivo'] }}</p>
        @endif
        <p>Puedes volver a enviar tu publicación desde el formulario correspondiente:</p>
        <p><a href="{{ $data['url'] }}" style="color:#0d6efd;">Enviar nuevamente</a></p>
        <p>Gracias por confiar en nosotros.</p>
        <p>Saludos,<br>El equipo de Bolsa de Trabajo</p>
    </div>
</body>
</html>
