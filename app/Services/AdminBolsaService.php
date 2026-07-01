<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminBolsaService
{
    public function __construct(private FileUploadService $fileUploadService)
    {
    }

    public function listPendingValidations()
    {
        $query = DB::table('registro_bolsadetrabajo_empresa as e')
            ->join('publicaciones_trabajo as p', 'e.id_empresa', '=', 'p.id_empresa')
            ->select(
                'e.*',
                'p.id_publicacion',
                'p.titulo_puesto',
                'p.descripcion_puesto',
                'p.requisitos',
                'p.imagen_trabajo',
                'p.modalidad',
                'p.categoria',
                'p.salario',
                'p.ubicacion',
                'p.fecha_inicio_convocatoria',
                'p.fecha_limite_postulacion'
            );

        if (Schema::hasColumn('registro_bolsadetrabajo_empresa', 'estado')) {
            $query->where(function ($subQuery) {
                $subQuery->where('e.estado', 'PENDIENTE')
                    ->orWhere('e.estado', 'Pendiente')
                    ->orWhereNull('e.estado');
            });
        }

        return $query->get();
    }

    public function listApprovedJobs()
    {
        $query = DB::table('empresas_bolsadetrabajo_aprobadas');

        if (Schema::hasColumn('empresas_bolsadetrabajo_aprobadas', 'estado')) {
            $query->where(function ($q) {
                $q->where('estado', '<>', 'Desactivado')
                    ->orWhereNull('estado');
            });
        }

        return $query->get();
    }

    public function listRejectedJobs()
    {
        if (Schema::hasTable('empresas_bolsadetrabajo_rechazadas')) {
            return DB::table('empresas_bolsadetrabajo_rechazadas')->get();
        }

        return DB::table('registro_bolsadetrabajo_empresa as e')
            ->join('publicaciones_trabajo as p', 'e.id_empresa', '=', 'p.id_empresa')
            ->where('e.estado', 'RECHAZADO')
            ->select(
                'e.*',
                'p.id_publicacion',
                'p.titulo_puesto',
                'p.descripcion_puesto',
                'p.requisitos',
                'p.imagen_trabajo',
                'p.modalidad',
                'p.categoria',
                'p.salario',
                'p.ubicacion',
                'p.fecha_inicio_convocatoria',
                'p.fecha_limite_postulacion'
            )
            ->get();
    }

    public function getValidationDetail(int $id)
    {
        return DB::table('registro_bolsadetrabajo_empresa as e')
            ->join('publicaciones_trabajo as p', 'e.id_empresa', '=', 'p.id_empresa')
            ->where('e.id_empresa', $id)
            ->select('e.*', 'p.*')
            ->first();
    }

    public function normalizeValidationDocumentPath(object $empresa): object
    {
        if (!empty($empresa->documento_validacion)) {
            $originalPath = public_path($empresa->documento_validacion);
            if (!file_exists($originalPath)) {
                $fallback = public_path('documentos/' . basename($empresa->documento_validacion));
                if (file_exists($fallback)) {
                    $empresa->documento_validacion = 'documentos/' . basename($empresa->documento_validacion);
                }
            }
        }

        return $empresa;
    }

    public function deleteApprovalDocument(int $id): void
    {
        $empresa = DB::table('empresas_bolsadetrabajo_aprobadas')->where('id_aprobado', $id)->first();

        if (!$empresa || empty($empresa->documento_aprobacion_pdf)) {
            return;
        }

        $this->fileUploadService->deletePublicFile($empresa->documento_aprobacion_pdf);

        DB::table('empresas_bolsadetrabajo_aprobadas')
            ->where('id_aprobado', $id)
            ->update(['documento_aprobacion_pdf' => null]);
    }

    public function deactivatePublication(int $id): void
    {
        $empresa = DB::table('empresas_bolsadetrabajo_aprobadas')->where('id_aprobado', $id)->first();

        if (!$empresa) {
            throw new \RuntimeException('No se encontró la publicación.');
        }

        $registroData = [
            'id_aprobado' => $empresa->id_aprobado,
            'id_empresa_original' => $empresa->id_empresa_original ?? null,
            'id_publicacion_original' => $empresa->id_publicacion_original ?? null,
            'id_usuario_aprobador' => $empresa->id_usuario_aprobador ?? null,
            'nombre_empresa' => $empresa->nombre_empresa ?? null,
            'ruc' => $empresa->ruc ?? null,
            'correo_electronico' => $empresa->correo_electronico ?? null,
            'telefono' => $empresa->telefono ?? null,
            'responsable_representante' => $empresa->responsable_representante ?? null,
            'direccion' => $empresa->direccion ?? null,
            'documento_validacion' => $empresa->documento_validacion ?? null,
            'titulo_puesto' => $empresa->titulo_puesto ?? null,
            'descripcion_puesto' => $empresa->descripcion_puesto ?? null,
            'requisitos' => $empresa->requisitos ?? null,
            'imagen_trabajo' => $empresa->imagen_trabajo ?? null,
            'modalidad' => $empresa->modalidad ?? null,
            'categoria' => $empresa->categoria ?? null,
            'salario' => $empresa->salario ?? null,
            'ubicacion' => $empresa->ubicacion ?? null,
            'fecha_inicio_convocatoria' => $empresa->fecha_inicio_convocatoria ?? null,
            'fecha_limite_postulacion' => $empresa->fecha_limite_postulacion ?? null,
            'estado' => 'Desactivado',
            'documento_aprobacion_pdf' => $empresa->documento_aprobacion_pdf ?? null,
            'fecha_aprobacion' => $empresa->fecha_aprobacion ?? null,
            'fecha_desactivacion' => now(),
        ];

        DB::transaction(function () use ($id, $registroData) {
            DB::table('registro_publicidad_bolsa_trabajo')->insert($registroData);
            DB::table('publicaciones_publicas')->where('id_aprobado', $id)->delete();
            DB::table('empresas_bolsadetrabajo_aprobadas')->where('id_aprobado', $id)->delete();
        });
    }

    public function listDeactivatedPublications()
    {
        return DB::table('registro_publicidad_bolsa_trabajo')->get();
    }

    public function getDeactivatedPublication(int $id)
    {
        return DB::table('registro_publicidad_bolsa_trabajo')->where('id_publicidad', $id)->first();
    }

    public function restorePublication(int $id): void
    {
        $registro = DB::table('registro_publicidad_bolsa_trabajo')->where('id_publicidad', $id)->first();

        if (!$registro) {
            throw new \RuntimeException('Registro no encontrado.');
        }

        $aprobadoData = [
            'id_aprobado' => $registro->id_aprobado,
            'id_empresa_original' => $registro->id_empresa_original,
            'id_publicacion_original' => $registro->id_publicacion_original,
            'id_usuario_aprobador' => $registro->id_usuario_aprobador,
            'nombre_empresa' => $registro->nombre_empresa,
            'ruc' => $registro->ruc,
            'correo_electronico' => $registro->correo_electronico,
            'telefono' => $registro->telefono,
            'responsable_representante' => $registro->responsable_representante,
            'direccion' => $registro->direccion,
            'documento_validacion' => $registro->documento_validacion,
            'titulo_puesto' => $registro->titulo_puesto,
            'descripcion_puesto' => $registro->descripcion_puesto,
            'requisitos' => $registro->requisitos,
            'imagen_trabajo' => $registro->imagen_trabajo,
            'modalidad' => $registro->modalidad,
            'categoria' => $registro->categoria,
            'salario' => $registro->salario,
            'ubicacion' => $registro->ubicacion,
            'fecha_inicio_convocatoria' => $registro->fecha_inicio_convocatoria,
            'fecha_limite_postulacion' => $registro->fecha_limite_postulacion,
            'estado' => 'Aprobado',
            'documento_aprobacion_pdf' => $registro->documento_aprobacion_pdf,
            'fecha_aprobacion' => $registro->fecha_aprobacion,
        ];

        DB::transaction(function () use ($aprobadoData, $registro) {
            DB::table('empresas_bolsadetrabajo_aprobadas')->insert($aprobadoData);
            DB::table('registro_publicidad_bolsa_trabajo')->where('id_publicidad', $registro->id_publicidad)->delete();
        });
    }
}
