<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DesactivarProductosVencidos extends Command
{
    protected $signature = 'productos:desactivar-vencidos';

    protected $description = 'Desactiva productos cuya fecha de fin ha vencido';

    public function handle()
    {
        $hoy = Carbon::now()->startOfDay();

        // Desactivar en empresas_producto_aprobadas
        $desactivadosAprobados = DB::table('empresas_producto_aprobadas')
            ->where('fecha_fin', '<', $hoy)
            ->where('estado', '!=', 'Desactivado')
            ->update(['estado' => 'Desactivado']);

        // Desactivar en productos_publicos
        $desactivadosPublicos = DB::table('productos_publicos')
            ->where('fecha_fin', '<', $hoy)
            ->where('estado', '!=', 'Desactivado')
            ->update(['estado' => 'Desactivado']);

        $total = $desactivadosAprobados + $desactivadosPublicos;

        $this->info("✓ {$total} productos desactivados correctamente");
        $this->info("  - Aprobados: {$desactivadosAprobados}");
        $this->info("  - Públicos: {$desactivadosPublicos}");

        return 0;
    }
}
