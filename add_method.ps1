$file = 'app\Http\Controllers\ProductoController.php'
$content = Get-Content $file -Raw

$newMethod = @"

    // =====================================================
    // PUBLICIDAD PRODUCTOS
    // =====================================================

    public function publicidadProductos()
    {
        `$hoy = \Carbon\Carbon::now()->startOfDay();

        `$productos = DB::table('productos_empresa as p')
            ->join('registro_empresa_producto as e', 'p.id_empresa_producto', '=', 'e.id_empresa_producto')
            ->where('p.estado', 'Aprobado')
            ->where('p.fecha_fin', '>=', `$hoy)
            ->select('p.*', 'e.nombre_empresa', 'e.correo_electronico', 'e.telefono')
            ->orderBy('p.fecha_inicio', 'desc')
            ->get();

        return view('publicidad.productos', compact('productos'));
    }
"@

# Replace the closing brace with method + closing brace
$content = $content -replace '}$', ($newMethod + "`n}")

$content | Set-Content $file
Write-Host "Method added successfully!"
