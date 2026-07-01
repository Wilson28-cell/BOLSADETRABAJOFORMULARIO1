<?php
$file = __DIR__ . '/app/Http/Controllers/ProductoController.php';
$content = file_get_contents($file);

$newMethod = <<<'EOF'

    // =====================================================
    // PUBLICIDAD PRODUCTOS
    // =====================================================

    public function publicidadProductos()
    {
        $hoy = \Carbon\Carbon::now()->startOfDay();

        $productos = DB::table('productos_empresa as p')
            ->join('registro_empresa_producto as e', 'p.id_empresa_producto', '=', 'e.id_empresa_producto')
            ->where('p.estado', 'Aprobado')
            ->where('p.fecha_fin', '>=', $hoy)
            ->select('p.*', 'e.nombre_empresa', 'e.correo_electronico', 'e.telefono')
            ->orderBy('p.fecha_inicio', 'desc')
            ->get();

        return view('publicidad.productos', compact('productos'));
    }
EOF;

// Replace the closing brace with method + closing brace
$content = preg_replace('/}$/', $newMethod . "\n}", $content);

file_put_contents($file, $content);
echo "Method added successfully!";
?>
