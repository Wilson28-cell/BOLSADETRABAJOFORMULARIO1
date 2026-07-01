<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('servicios_publicos')->select('id_publico_servicio','nombre_servicio','estado')->orderBy('id_publico_servicio')->get();

foreach ($rows as $r) {
    echo "{$r->id_publico_servicio} | {$r->nombre_servicio} | {$r->estado}\n";
}

return 0;
