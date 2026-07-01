<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RestoreServiciosPublicos extends Command
{
    protected $signature = 'servicios:restore-missing';
    protected $description = 'Restaurar entradas faltantes en servicios_publicos desde empresas_servicio_aprobadas';

    public function handle()
    {
        $this->info('Buscando aprobados sin publicacion pública...');

        $aprobados = DB::table('empresas_servicio_aprobadas')->get();
        $restored = 0;

        foreach ($aprobados as $a) {
            $exists = DB::table('servicios_publicos')->where('id_aprobado', $a->id_aprobado)->first();
            if ($exists) continue;

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
                'estado' => $a->estado ?? 'Desactivado',
                'fecha_inicio' => $a->fecha_inicio,
                'fecha_fin' => $a->fecha_fin,
                'fecha_publicacion' => now(),
            ];

            DB::table('servicios_publicos')->insert($data);
            $restored++;
        }

        $this->info("Restauradas $restored publicaciones (si las había).");
        return 0;
    }
}
