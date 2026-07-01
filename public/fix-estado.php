<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

try {
    echo "<h2>Expandiendo columna 'estado'...</h2>";
    
    // Expandir en empresas_producto_aprobadas
    DB::statement('ALTER TABLE `empresas_producto_aprobadas` MODIFY COLUMN `estado` VARCHAR(50) NOT NULL');
    echo "<p>✅ Columna expandida en <strong>empresas_producto_aprobadas</strong></p>";
    
    // Expandir en productos_publicos
    DB::statement('ALTER TABLE `productos_publicos` MODIFY COLUMN `estado` VARCHAR(50) NOT NULL');
    echo "<p>✅ Columna expandida en <strong>productos_publicos</strong></p>";
    
    echo "<hr>";
    echo "<p><strong>¡Listo!</strong> Ahora puedes desactivar publicaciones sin errores.</p>";
    echo "<p><a href='/admin/productos'>Ir al panel de productos</a></p>";
    
} catch (\Exception $e) {
    echo "<h2>❌ Error:</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
