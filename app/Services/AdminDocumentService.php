<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class AdminDocumentService
{
    public function __construct(private FileUploadService $fileUploadService)
    {
    }

    public function approvePublication(int $id, ?UploadedFile $documentoSubgerencia): void
    {
        $empresa = DB::table('registro_bolsadetrabajo_empresa as e')
            ->join('publicaciones_trabajo as p', 'e.id_empresa', '=', 'p.id_empresa')
            ->where('e.id_empresa', $id)
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
            ->first();

        if (!$empresa) {
            throw new \RuntimeException('Oferta no encontrada.');
        }

        $documentoSubgerenciaPath = null;
        if ($documentoSubgerencia) {
            $documentoSubgerenciaPath = $this->fileUploadService->storePublicFile(
                $documentoSubgerencia,
                'BolsaTrabajo/documentosAprobadosSubgerenciaEmpleo'
            );
        }

        $nuevoDocumento = $this->fileUploadService->copyPublicFile(
            $empresa->documento_validacion,
            'BolsaTrabajo/documentosBolsaDeTrabajoAprobados'
        ) ?? $empresa->documento_validacion;

        $nuevaImagen = $empresa->imagen_trabajo ?: '';
        $imagenAprobada = $this->fileUploadService->copyPublicFile(
            $empresa->imagen_trabajo,
            'BolsaTrabajo/imagenesBolsaTrabajoAprobados'
        );

        if ($imagenAprobada) {
            $nuevaImagen = $imagenAprobada;
        }

        DB::transaction(function () use ($empresa, $nuevoDocumento, $nuevaImagen, $documentoSubgerenciaPath) {
            $idAprobado = DB::table('empresas_bolsadetrabajo_aprobadas')->insertGetId([
                'id_empresa_original' => $empresa->id_empresa,
                'id_publicacion_original' => $empresa->id_publicacion,
                'id_usuario_aprobador' => 1,
                'nombre_empresa' => $empresa->nombre_empresa,
                'ruc' => $empresa->ruc,
                'correo_electronico' => $empresa->correo_electronico,
                'telefono' => $empresa->telefono,
                'responsable_representante' => $empresa->responsable_representante,
                'direccion' => $empresa->direccion,
                'documento_validacion' => $nuevoDocumento,
                'documento_aprobacion_pdf' => $documentoSubgerenciaPath ?: '',
                'titulo_puesto' => $empresa->titulo_puesto,
                'descripcion_puesto' => $empresa->descripcion_puesto,
                'requisitos' => $empresa->requisitos,
                'imagen_trabajo' => $nuevaImagen,
                'modalidad' => $empresa->modalidad,
                'categoria' => $empresa->categoria,
                'salario' => $empresa->salario,
                'ubicacion' => $empresa->ubicacion,
                'fecha_inicio_convocatoria' => $empresa->fecha_inicio_convocatoria,
                'fecha_limite_postulacion' => $empresa->fecha_limite_postulacion,
            ]);

            try {
                Mail::to($empresa->correo_electronico)->send(new \App\Mail\PublicacionAprobada([
                    'nombre_empresa' => $empresa->nombre_empresa,
                    'titulo_puesto' => $empresa->titulo_puesto,
                    'descripcion_puesto' => $empresa->descripcion_puesto,
                    'url' => url('/'),
                ]));
            } catch (\Exception $e) {
                // Continuar incluso si el correo falla.
            }

            DB::table('publicaciones_trabajo')->where('id_empresa', $empresa->id_empresa)->delete();
            DB::table('registro_bolsadetrabajo_empresa')->where('id_empresa', $empresa->id_empresa)->delete();
        });
    }

    public function rejectPublication(int $id): void
    {
        $empresa = DB::table('registro_bolsadetrabajo_empresa as e')
            ->join('publicaciones_trabajo as p', 'e.id_empresa', '=', 'p.id_empresa')
            ->where('e.id_empresa', $id)
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
            ->first();

        if (!$empresa) {
            throw new \RuntimeException('Oferta no encontrada.');
        }

        DB::transaction(function () use ($empresa) {
            if (Schema::hasTable('empresas_bolsadetrabajo_rechazadas')) {
                $rechazadoData = [
                    'id_empresa_original' => $empresa->id_empresa,
                    'id_publicacion_original' => $empresa->id_publicacion,
                    'nombre_empresa' => $empresa->nombre_empresa,
                    'ruc' => $empresa->ruc,
                    'correo_electronico' => $empresa->correo_electronico,
                    'telefono' => $empresa->telefono,
                    'responsable_representante' => $empresa->responsable_representante,
                    'direccion' => $empresa->direccion,
                    'documento_validacion' => $empresa->documento_validacion,
                    'titulo_puesto' => $empresa->titulo_puesto,
                    'descripcion_puesto' => $empresa->descripcion_puesto,
                    'requisitos' => $empresa->requisitos,
                    'imagen_trabajo' => $empresa->imagen_trabajo,
                    'modalidad' => $empresa->modalidad,
                    'categoria' => $empresa->categoria,
                    'salario' => $empresa->salario,
                    'ubicacion' => $empresa->ubicacion,
                    'fecha_inicio_convocatoria' => $empresa->fecha_inicio_convocatoria,
                    'fecha_limite_postulacion' => $empresa->fecha_limite_postulacion,
                    'fecha_rechazo' => now(),
                ];

                if (Schema::hasColumn('empresas_bolsadetrabajo_rechazadas', 'id_usuario_rechazo')) {
                    $rechazadoData['id_usuario_rechazo'] = 1;
                }

                if (Schema::hasColumn('empresas_bolsadetrabajo_rechazadas', 'motivo_rechazo')) {
                    $rechazadoData['motivo_rechazo'] = '';
                }

                if (Schema::hasColumn('empresas_bolsadetrabajo_rechazadas', 'veces_restaurado')) {
                    $rechazadoData['veces_restaurado'] = 0;
                }

                if (Schema::hasColumn('empresas_bolsadetrabajo_rechazadas', 'created_at')) {
                    $rechazadoData['created_at'] = now();
                }

                if (Schema::hasColumn('empresas_bolsadetrabajo_rechazadas', 'updated_at')) {
                    $rechazadoData['updated_at'] = now();
                }

                DB::table('empresas_bolsadetrabajo_rechazadas')->insert($rechazadoData);
            }

            DB::table('publicaciones_trabajo')->where('id_empresa', $empresa->id_empresa)->delete();
            DB::table('registro_bolsadetrabajo_empresa')->where('id_empresa', $empresa->id_empresa)->delete();
        });
    }

    public function restorePublication(int $id): void
    {
        if (Schema::hasTable('empresas_bolsadetrabajo_rechazadas')) {
            $activa = DB::table('empresas_bolsadetrabajo_rechazadas')->where('id_rechazado', $id)->first();

            if (!$activa) {
                throw new \RuntimeException('No se encontró la oferta para restaurar.');
            }

            DB::transaction(function () use ($activa, $id) {
                $registroData = [
                    'nombre_empresa' => $activa->nombre_empresa,
                    'ruc' => $activa->ruc,
                    'correo_electronico' => $activa->correo_electronico,
                    'telefono' => $activa->telefono,
                    'responsable_representante' => $activa->responsable_representante,
                    'direccion' => $activa->direccion,
                    'documento_validacion' => $activa->documento_validacion,
                    'estado' => 'PENDIENTE',
                ];

                if (Schema::hasColumn('registro_bolsadetrabajo_empresa', 'updated_at')) {
                    $registroData['updated_at'] = now();
                }

                $existingregistro = DB::table('registro_bolsadetrabajo_empresa')->where('id_empresa', $activa->id_empresa_original)->first();
                if ($existingregistro) {
                    DB::table('registro_bolsadetrabajo_empresa')->where('id_empresa', $activa->id_empresa_original)->update($registroData);
                } else {
                    $registroData['id_empresa'] = $activa->id_empresa_original;
                    if (Schema::hasColumn('registro_bolsadetrabajo_empresa', 'created_at')) {
                        $registroData['created_at'] = now();
                    }
                    DB::table('registro_bolsadetrabajo_empresa')->insert($registroData);
                }

                $publicacionData = [
                    'titulo_puesto' => $activa->titulo_puesto,
                    'descripcion_puesto' => $activa->descripcion_puesto,
                    'requisitos' => $activa->requisitos,
                    'imagen_trabajo' => $activa->imagen_trabajo,
                    'modalidad' => $activa->modalidad,
                    'categoria' => $activa->categoria,
                    'salario' => $activa->salario,
                    'ubicacion' => $activa->ubicacion,
                    'fecha_inicio_convocatoria' => $activa->fecha_inicio_convocatoria,
                    'fecha_limite_postulacion' => $activa->fecha_limite_postulacion,
                ];

                if (Schema::hasColumn('publicaciones_trabajo', 'estado')) {
                    $publicacionData['estado'] = 'PENDIENTE';
                }

                if (Schema::hasColumn('publicaciones_trabajo', 'updated_at')) {
                    $publicacionData['updated_at'] = now();
                }

                $existingpublicacion = DB::table('publicaciones_trabajo')->where('id_publicacion', $activa->id_publicacion_original)->first();
                if ($existingpublicacion) {
                    DB::table('publicaciones_trabajo')->where('id_publicacion', $activa->id_publicacion_original)->update($publicacionData);
                } else {
                    $publicacionData['id_publicacion'] = $activa->id_publicacion_original;
                    $publicacionData['id_empresa'] = $activa->id_empresa_original;
                    if (Schema::hasColumn('publicaciones_trabajo', 'created_at')) {
                        $publicacionData['created_at'] = now();
                    }
                    DB::table('publicaciones_trabajo')->insert($publicacionData);
                }

                if (Schema::hasColumn('empresas_bolsadetrabajo_rechazadas', 'veces_restaurado')) {
                    DB::table('empresas_bolsadetrabajo_rechazadas')
                        ->where('id_rechazado', $id)
                        ->update(['veces_restaurado' => intval($activa->veces_restaurado ?? 0) + 1]);
                }

                DB::table('empresas_bolsadetrabajo_rechazadas')->where('id_rechazado', $id)->delete();
            });

            return;
        }

        $empresa = DB::table('registro_bolsadetrabajo_empresa')->where('id_empresa', $id)->first();

        if (!$empresa) {
            throw new \RuntimeException('No se encontró la oferta para restaurar.');
        }

        $veces = intval($empresa->veces_restaurado ?? 0);
        if ($veces >= 2) {
            throw new \RuntimeException('Ya alcanzó el límite de restauraciones para esta oferta.');
        }

        DB::table('registro_bolsadetrabajo_empresa')->where('id_empresa', $id)->update([
            'estado' => 'PENDIENTE',
            'veces_restaurado' => $veces + 1,
        ]);

        if (Schema::hasColumn('publicaciones_trabajo', 'estado')) {
            DB::table('publicaciones_trabajo')->where('id_empresa', $id)->update(['estado' => 'PENDIENTE']);
        }
    }

    public function uploadApprovalDocument(int $id, UploadedFile $documentoPdf): void
    {
        $rutaPdf = $this->fileUploadService->storePublicFile(
            $documentoPdf,
            'BolsaTrabajo/documentosAprobadosSubgerenciaEmpleo'
        );

        $empresaAprobada = DB::table('empresas_bolsadetrabajo_aprobadas')->where('id_aprobado', $id)->first();

        if ($empresaAprobada && !empty($empresaAprobada->documento_aprobacion_pdf)) {
            $this->fileUploadService->deletePublicFile($empresaAprobada->documento_aprobacion_pdf);
        }

        DB::table('empresas_bolsadetrabajo_aprobadas')->where('id_aprobado', $id)->update([
            'documento_aprobacion_pdf' => $rutaPdf,
        ]);

        $empresa = DB::table('empresas_bolsadetrabajo_aprobadas')->where('id_aprobado', $id)->first();

        DB::table('publicaciones_publicas')->insert([
            'id_aprobado' => $empresa->id_aprobado,
            'nombre_empresa' => $empresa->nombre_empresa,
            'titulo_puesto' => $empresa->titulo_puesto,
            'descripcion_puesto' => $empresa->descripcion_puesto,
            'requisitos' => $empresa->requisitos,
            'imagen_trabajo' => $empresa->imagen_trabajo,
            'modalidad' => $empresa->modalidad,
            'categoria' => $empresa->categoria,
            'salario' => $empresa->salario,
            'ubicacion' => $empresa->ubicacion,
            'fecha_inicio_convocatoria' => $empresa->fecha_inicio_convocatoria,
            'fecha_limite_postulacion' => $empresa->fecha_limite_postulacion,
        ]);
    }
}
