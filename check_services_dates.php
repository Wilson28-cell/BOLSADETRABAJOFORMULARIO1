<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$services = DB::table('servicios_publicos')
    ->select('id_publico_servicio', 'nombre_servicio', 'estado', 'fecha_fin')
    ->get();

echo "Servicios en la BD:\n";
foreach ($services as $s) {
    echo "ID: {$s->id_publico_servicio} | Nombre: {$s->nombre_servicio} | Estado: {$s->estado} | Fecha Fin: {$s->fecha_fin}\n";
}

echo "\nFecha de hoy: " . date('Y-m-d') . "\n";

// Verificar cuál sería mostrado
$today = date('Y-m-d');
$visible = DB::table('servicios_publicos')
    ->where('estado', 'Publicado')
    ->whereDate('fecha_fin', '>=', $today)
    ->get();

echo "\nServicios que deberían mostrarse:\n";
if ($visible->isEmpty()) {
    echo "❌ NINGUNO - Todos están vencidos o con estado incorrecto\n";
} else {
    foreach ($visible as $s) {
        echo "✓ {$s->nombre_servicio}\n";
    }
}
