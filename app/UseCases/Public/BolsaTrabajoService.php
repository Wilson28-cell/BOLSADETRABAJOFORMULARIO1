<?php

namespace App\UseCases\Public;

use App\UseCases\Common\FileUploadInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;

class BolsaTrabajoService
{
    public function __construct(private FileUploadInterface $fileUploadService)
    {
    }

    public function createPublication(array $data, UploadedFile $document, UploadedFile $image): void
    {
        $documentPath = $this->fileUploadService->storePublicFile($document, 'BolsaTrabajo/documentos');
        $imagePath = $this->fileUploadService->storePublicFile($image, 'BolsaTrabajo/imagenes');

        try {
            DB::transaction(function () use ($data, $documentPath, $imagePath) {
                $companyId = DB::table('registro_bolsadetrabajo_empresa')->insertGetId([
                    'nombre_empresa' => $data['nombre_empresa'],
                    'ruc' => $data['ruc'],
                    'correo_electronico' => $data['correo_electronico'],
                    'telefono' => $data['telefono'],
                    'responsable_representante' => $data['responsable_representante'],
                    'direccion' => $data['direccion'],
                    'documento_validacion' => $documentPath,
                    'estado' => 'PENDIENTE',
                ]);

                DB::table('publicaciones_trabajo')->insert([
                    'id_empresa' => $companyId,
                    'titulo_puesto' => $data['titulo_puesto'],
                    'descripcion_puesto' => $data['descripcion_puesto'],
                    'requisitos' => $data['requisitos'],
                    'imagen_trabajo' => $imagePath,
                    'modalidad' => $data['modalidad'],
                    'categoria' => $data['categoria'],
                    'salario' => $data['salario'],
                    'ubicacion' => $data['ubicacion'],
                    'fecha_inicio_convocatoria' => $data['fecha_inicio_convocatoria'],
                    'fecha_limite_postulacion' => $data['fecha_limite_postulacion'],
                ]);
            });
        } catch (QueryException $exception) {
            $this->fileUploadService->deletePublicFile($documentPath);
            $this->fileUploadService->deletePublicFile($imagePath);
            throw $exception;
        }
    }

    public function listPublicJobs(array $filters = []): Collection
    {
        $query = DB::table('publicaciones_publicas');

        if (!empty($filters['buscar'])) {
            $buscar = trim($filters['buscar']);
            $query->where(function ($sub) use ($buscar) {
                $sub->where('titulo_puesto', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('descripcion_puesto', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('ubicacion', 'LIKE', '%' . $buscar . '%');
            });
        }

        if (!empty($filters['modalidad'])) {
            $query->where('modalidad', $filters['modalidad']);
        }

        if (!empty($filters['categoria'])) {
            $query->where('categoria', $filters['categoria']);
        }

        if (!empty($filters['salario_min'])) {
            $query->where('salario', '>=', (float) $filters['salario_min']);
        }

        $estado = $filters['estado'] ?? null;
        if ($estado === 'vencidas') {
            $query->where('fecha_limite_postulacion', '<', now()->format('Y-m-d'));
        } else {
            $query->where('fecha_limite_postulacion', '>=', now()->format('Y-m-d'));
        }

        return $query->orderBy('fecha_publicacion_publica', 'desc')->get();
    }

    public function getPublicJobById(int $id): ?object
    {
        return DB::table('publicaciones_publicas')->where('id_aprobado', $id)->first();
    }

    public function getPublicCategories(): Collection
    {
        return DB::table('publicaciones_publicas')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');
    }

    public function submitPostulacion(int $jobId, array $data, UploadedFile $curriculumPdf): void
    {
        $curriculumPath = $this->fileUploadService->storePublicFile($curriculumPdf, 'curriculums');

        try {
            DB::transaction(function () use ($jobId, $data, $curriculumPath) {
                $postulanteId = DB::table('postulantes')->insertGetId([
                    'nombres' => $data['nombres'],
                    'apellidos' => $data['apellidos'],
                    'dni' => $data['dni'],
                    'correo_electronico' => $data['correo_electronico'],
                    'telefono' => $data['telefono'],
                    'direccion' => $data['direccion'],
                    'fecha_nacimiento' => $data['fecha_nacimiento'],
                    'genero' => $data['genero'],
                    'password' => Hash::make($data['password']),
                ]);

                DB::table('postulaciones')->insert([
                    'id_publica' => $jobId,
                    'id_postulante' => $postulanteId,
                    'mensaje_postulacion' => $data['mensaje_postulacion'] ?? null,
                    'curriculum_pdf' => $curriculumPath,
                ]);
            });
        } catch (QueryException $exception) {
            $this->fileUploadService->deletePublicFile($curriculumPath);
            throw $exception;
        }
    }
}
