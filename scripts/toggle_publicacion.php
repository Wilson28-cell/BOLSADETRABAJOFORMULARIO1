<?php
if ($argc < 3) {
    echo "Usage: php toggle_publicacion.php <deactivate|reactivate> <id>\n";
    exit(1);
}
$action = $argv[1];
$id = intval($argv[2]);

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

if ($action === 'deactivate') {
    $updated = DB::table('servicios_publicos')->where('id_publico_servicio', $id)->update(['estado' => 'Desactivado']);
    echo $updated ? "Publicacion $id desactivada\n" : "No se encontró publicacion $id\n";
} elseif ($action === 'reactivate') {
    $updated = DB::table('servicios_publicos')->where('id_publico_servicio', $id)->update(['estado' => 'Publicado']);
    echo $updated ? "Publicacion $id reactivada\n" : "No se encontró publicacion $id\n";
} else {
    echo "Unknown action: $action\n";
    exit(1);
}

return 0;
