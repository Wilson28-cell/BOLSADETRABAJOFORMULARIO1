<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicación Aprobada</title>
</head>
<body style="font-family:Arial, sans-serif; color:#333;">
    <div style="max-width:600px; margin:0 auto; padding:20px; background:#f8f9fa; border:1px solid #e9ecef; border-radius:8px;">
        <h1 style="font-size:24px; margin-bottom:16px;">Publicación aprobada</h1>
        <p>Hola,</p>
        <p>Tu publicación para <strong>{{ $data['titulo_puesto'] }}</strong> en <strong>{{ $data['nombre_empresa'] }}</strong> ha sido aprobada.</p>
        <p>Detalles de la oferta:</p>
        <ul>
            <li><strong>Título:</strong> {{ $data['titulo_puesto'] }}</li>
            <li><strong>Empresa:</strong> {{ $data['nombre_empresa'] }}</li>
        </ul>
        <p>{{ $data['descripcion_puesto'] }}</p>
        <p>Ya está disponible en el portal. Puedes revisarla aquí:</p>
        <p><a href="{{ $data['url'] }}" style="color:#0d6efd;">Ver plataforma de empleo</a></p>
        <p>Gracias por publicar con nosotros.</p>
        <p>Saludos,<br>El equipo de Bolsa de Trabajo</p>
    </div>
</body>
</html>
