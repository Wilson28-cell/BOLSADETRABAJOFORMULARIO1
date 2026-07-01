<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicación Aprobada</title>
</head>
<body style="font-family:Arial, sans-serif; color:#333;">
    <div style="max-width:600px; margin:0 auto; padding:20px; background:#f8f9fa; border:1px solid #e9ecef; border-radius:8px;">
        <h1 style="font-size:24px; margin-bottom:16px;">Publicación aprobada</h1>
        <p>Hola {{ $data['nombre_empresa'] ?? 'Usuario' }},</p>
        <p>Tu publicación de <strong>{{ $data['tipo'] === 'servicio' ? 'servicio' : 'producto' }}</strong> <strong>{{ $data['titulo'] }}</strong> ha sido aprobada.</p>
        @if(!empty($data['descripcion']))
            <p>{{ $data['descripcion'] }}</p>
        @endif
        <p>Ya está disponible en el portal. Puedes revisarla aquí:</p>
        <p><a href="{{ $data['url'] }}" style="color:#0d6efd;">Ver publicación</a></p>
        @if(!empty($data['documento_aprobacion_pdf']))
            <p>El documento de validación se ha adjuntado a este correo.</p>
        @endif
        <p>Gracias por publicar con nosotros.</p>
        <p>Saludos,<br>El equipo de Bolsa de Trabajo</p>
    </div>
</body>
</html>
