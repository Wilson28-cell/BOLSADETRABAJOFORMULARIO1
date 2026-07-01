<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Boot the application
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArrayInput([]),
    new Symfony\Component\Console\Output\BufferedOutput()
);

use Illuminate\Support\Facades\DB;

$aprobados = DB::table('empresas_servicio_aprobadas')->get();
$restored = 0;

foreach ($aprobados as $a) {
    $exists = DB::table('servicios_publicos')->where('id_aprobado', $a->id_aprobado)->first();
    if ($exists) continue;

    $estado = $a->estado ?? 'Desactivado';
    if (strtolower($estado) === 'aprobado') $estado = 'Publicado';

    $data = [
        'id_aprobado' => $a->id_aprobado,
        'nombre_empresa' => $a->nombre_empresa,
        'nombre_servicio' => $a->nombre_servicio,
        'descripcion' => $a->descripcion,
        'categoria' => $a->categoria,
        'ubicacion_ciudad' => $a->ubicacion_ciudad,
        'telefono_contacto' => $a->telefono_contacto,
        'redes_sociales' => $a->redes_sociales,
        'correo_contacto' => $a->correo_contacto,
        'direccion_atencion' => $a->direccion_atencion,
        'imagen_servicio' => $a->imagen_servicio,
        'horario_atencion' => $a->horario_atencion,
                'estado' => $estado,
        'fecha_inicio' => $a->fecha_inicio,
        'fecha_fin' => $a->fecha_fin,
        'fecha_publicacion' => date('Y-m-d H:i:s'),
    ];

    DB::table('servicios_publicos')->insert($data);
    $restored++;
}

echo "Restauradas $restored publicaciones.\n";

// Terminate kernel
$kernel->terminate($input, $status);

return 0;
