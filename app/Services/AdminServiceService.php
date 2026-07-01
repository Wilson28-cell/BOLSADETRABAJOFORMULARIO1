<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminServiceService
{
    public function __construct(private FileUploadService $fileUploadService)
    {
    }

    public function listPendingServices()
    {
        return DB::table('registro_empresa_servicio as e')
            ->join('servicios_empresa as s', 'e.id_empresa_servicio', '=', 's.id_empresa_servicio')
            ->where('s.estado', 'Pendiente')
            ->select(
                'e.*',
                's.id_servicio',
                's.nombre_servicio',
                's.descripcion',
                's.categoria',
                's.ubicacion_ciudad',
                's.telefono_contacto',
                's.redes_sociales',
                's.correo_contacto',
                's.direccion_atencion',
                's.imagen_servicio',
                's.horario_atencion',
                's.estado as estado_servicio',
                's.fecha_inicio',
                's.fecha_fin'
            )
            ->get();
    }

    public function getPendingService(int $id)
    {
        return DB::table('registro_empresa_servicio as e')
            ->join('servicios_empresa as s', 'e.id_empresa_servicio', '=', 's.id_empresa_servicio')
            ->where('s.id_servicio', $id)
            ->select(
                'e.*',
                's.id_servicio',
                's.nombre_servicio',
                's.descripcion',
                's.categoria',
                's.ubicacion_ciudad',
                's.telefono_contacto',
                's.redes_sociales',
                's.correo_contacto',
                's.direccion_atencion',
                's.imagen_servicio',
                's.horario_atencion',
                's.estado as estado_servicio',
                's.fecha_inicio',
                's.fecha_fin'
            )
            ->first();
    }

    public function getRejectedService(int $id)
    {
        return DB::table('empresas_servicio_rechazadas')
            ->where('id_rechazado', $id)
            ->first();
    }

    public function getPublicService(int $id)
    {
        return DB::table('servicios_publicos')
            ->where('id_publico_servicio', $id)
            ->first();
    }

    public function listPublicServices(array $filters = [])
    {
        $query = DB::table('servicios_publicos')
            ->select('servicios_publicos.*', 'servicios_publicos.fecha_publicacion as fecha_registro');

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('nombre_empresa', 'like', '%' . $search . '%')
                    ->orWhere('nombre_servicio', 'like', '%' . $search . '%');
            });
        }

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        if (!empty($filters['fecha_inicio'])) {
            $query->whereDate('fecha_publicacion', '>=', $filters['fecha_inicio']);
        }

        if (!empty($filters['fecha_fin'])) {
            $query->whereDate('fecha_publicacion', '<=', $filters['fecha_fin']);
        }

        return $query->orderByDesc('fecha_publicacion')->get();
    }

    public function listRejectedServices()
    {
        return DB::table('empresas_servicio_rechazadas')
            ->select('empresas_servicio_rechazadas.*', 'empresas_servicio_rechazadas.fecha_rechazo as fecha_registro')
            ->orderByDesc('fecha_rechazo')
            ->get();
    }

    public function approveService(int $id, UploadedFile $documentoSubgerencia, int $approverId): void
    {
        $servicio = $this->getPendingService($id);
        if (!$servicio) {
            throw new \RuntimeException('Servicio pendiente no encontrado.');
        }

        $documentoPath = $this->fileUploadService->storePublicFile(
            $documentoSubgerencia,
            'Servicios/documentosServiciosAprobadosSubgerencia'
        );

        DB::transaction(function () use ($servicio, $documentoPath, $approverId) {
            $idAprobado = DB::table('empresas_servicio_aprobadas')->insertGetId([
                'id_empresa_original' => $servicio->id_empresa_servicio,
                'id_servicio_original' => $servicio->id_servicio,
                'id_usuario_aprobador' => $approverId,
                'nombre_empresa' => $servicio->nombre_empresa,
                'ruc' => $servicio->ruc,
                'correo_electronico' => $servicio->correo_electronico,
                'telefono' => $servicio->telefono,
                'responsable_representante' => $servicio->responsable_representante,
                'direccion' => $servicio->direccion,
                'documento_validacion' => $servicio->documento_validacion,
                'nombre_servicio' => $servicio->nombre_servicio,
                'descripcion' => $servicio->descripcion,
                'categoria' => $servicio->categoria,
                'ubicacion_ciudad' => $servicio->ubicacion_ciudad,
                'telefono_contacto' => $servicio->telefono_contacto,
                'redes_sociales' => $servicio->redes_sociales,
                'correo_contacto' => $servicio->correo_contacto,
                'direccion_atencion' => $servicio->direccion_atencion,
                'imagen_servicio' => $servicio->imagen_servicio,
                'horario_atencion' => $servicio->horario_atencion,
                'estado' => 'Aprobado',
                'documento_aprobacion' => $documentoPath,
                'fecha_inicio' => $servicio->fecha_inicio,
                'fecha_fin' => $servicio->fecha_fin,
            ]);

            DB::table('servicios_publicos')->insert([
                'id_aprobado' => $idAprobado,
                'nombre_empresa' => $servicio->nombre_empresa,
                'nombre_servicio' => $servicio->nombre_servicio,
                'descripcion' => $servicio->descripcion,
                'categoria' => $servicio->categoria,
                'ubicacion_ciudad' => $servicio->ubicacion_ciudad,
                'telefono_contacto' => $servicio->telefono_contacto,
                'redes_sociales' => $servicio->redes_sociales,
                'correo_contacto' => $servicio->correo_contacto,
                'direccion_atencion' => $servicio->direccion_atencion,
                'imagen_servicio' => $servicio->imagen_servicio,
                'horario_atencion' => $servicio->horario_atencion,
                'estado' => 'Publicado',
                'fecha_inicio' => $servicio->fecha_inicio,
                'fecha_fin' => $servicio->fecha_fin,
                'fecha_publicacion' => now(),
            ]);

            DB::table('servicios_empresa')->where('id_servicio', $servicio->id_servicio)->update(['estado' => 'Publicado']);
            DB::table('registro_empresa_servicio')->where('id_empresa_servicio', $servicio->id_empresa_servicio)->update(['estado' => 'Publicado']);
        });
    }

    public function rejectService(int $id, string $motivoRechazo, int $rejectorId): void
    {
        $servicio = $this->getPendingService($id);
        if (!$servicio) {
            throw new \RuntimeException('Servicio pendiente no encontrado.');
        }

        if ($servicio->estado_servicio !== 'Pendiente') {
            throw new \RuntimeException('Este servicio ya ha sido procesado.');
        }

        $rechazoData = [
            'id_empresa_original' => $servicio->id_empresa_servicio,
            'id_servicio_original' => $servicio->id_servicio,
            'id_usuario_rechazo' => $rejectorId,
            'nombre_empresa' => $servicio->nombre_empresa,
            'ruc' => $servicio->ruc,
            'correo_electronico' => $servicio->correo_electronico,
            'telefono' => $servicio->telefono,
            'responsable_representante' => $servicio->responsable_representante,
            'direccion' => $servicio->direccion,
            'documento_validacion' => $servicio->documento_validacion,
            'nombre_servicio' => $servicio->nombre_servicio,
            'descripcion' => $servicio->descripcion,
            'categoria' => $servicio->categoria,
            'ubicacion_ciudad' => $servicio->ubicacion_ciudad,
            'telefono_contacto' => $servicio->telefono_contacto,
            'redes_sociales' => $servicio->redes_sociales,
            'correo_contacto' => $servicio->correo_contacto,
            'direccion_atencion' => $servicio->direccion_atencion,
            'imagen_servicio' => $servicio->imagen_servicio,
            'horario_atencion' => $servicio->horario_atencion,
            'estado' => 'Rechazado',
            'motivo_rechazo' => $motivoRechazo,
            'fecha_inicio' => $servicio->fecha_inicio,
            'fecha_fin' => $servicio->fecha_fin,
            'fecha_rechazo' => now(),
        ];

        DB::transaction(function () use ($servicio, $rechazoData) {
            DB::table('empresas_servicio_rechazadas')->updateOrInsert(
                ['id_servicio_original' => $servicio->id_servicio],
                $rechazoData
            );

            DB::table('servicios_empresa')->where('id_servicio', $servicio->id_servicio)->update(['estado' => 'Rechazado']);
            DB::table('registro_empresa_servicio')->where('id_empresa_servicio', $servicio->id_empresa_servicio)->update(['estado' => 'Rechazado']);
        });
    }

    public function restoreService(int $id): void
    {
        $servicioRechazado = $this->getRejectedService($id);
        if (!$servicioRechazado) {
            throw new \RuntimeException('Servicio rechazado no encontrado.');
        }

        DB::transaction(function () use ($servicioRechazado) {
            DB::table('servicios_empresa')
                ->where('id_servicio', $servicioRechazado->id_servicio_original)
                ->update(['estado' => 'Pendiente']);

            DB::table('registro_empresa_servicio')
                ->where('id_empresa_servicio', $servicioRechazado->id_empresa_original)
                ->update(['estado' => 'Pendiente']);

            DB::table('empresas_servicio_rechazadas')
                ->where('id_rechazado', $servicioRechazado->id_rechazado)
                ->delete();
        });
    }

    public function deactivatePublishedService(int $id): void
    {
        $publicacion = $this->getPublicService($id);
        if (!$publicacion) {
            throw new \RuntimeException('Publicación de servicio no encontrada.');
        }

        DB::table('servicios_publicos')
            ->where('id_publico_servicio', $id)
            ->update(['estado' => 'Desactivado']);
    }

    public function reactivatePublishedService(int $id): void
    {
        $publicacion = $this->getPublicService($id);
        if (!$publicacion) {
            throw new \RuntimeException('Publicación de servicio no encontrada.');
        }

        DB::table('servicios_publicos')
            ->where('id_publico_servicio', $id)
            ->update(['estado' => 'Publicado']);
    }

    public function deletePublishedService(int $id): void
    {
        $publicacion = $this->getPublicService($id);
        if (!$publicacion) {
            throw new \RuntimeException('Publicación de servicio no encontrada.');
        }

        DB::transaction(function () use ($publicacion) {
            DB::table('servicios_publicos')
                ->where('id_publico_servicio', $publicacion->id_publico_servicio)
                ->delete();

            if (!empty($publicacion->id_aprobado)) {
                DB::table('empresas_servicio_aprobadas')
                    ->where('id_aprobado', $publicacion->id_aprobado)
                    ->delete();
            }
        });
    }
}
