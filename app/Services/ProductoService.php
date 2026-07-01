<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ProductoService
{
    public function __construct(private FileUploadService $fileUploadService)
    {
    }

    public function createProduct(array $data, UploadedFile $document, UploadedFile $image, Carbon $fechaInicio, Carbon $fechaFin): void
    {
        $documentPath = $this->fileUploadService->storePublicFile($document, 'Productos/documentosProductos');
        $imagePath = $this->fileUploadService->storePublicFile($image, 'Productos/imagenesProductos');

        try {
            DB::transaction(function () use ($data, $documentPath, $imagePath, $fechaInicio, $fechaFin) {
                $empresaData = [
                    'nombre_empresa' => $data['nombre_empresa'],
                    'ruc' => $data['ruc'],
                    'correo_electronico' => $data['correo_electronico'],
                    'telefono' => $data['telefono'],
                    'responsable_representante' => $data['responsable_representante'],
                    'direccion' => $data['direccion'],
                    'documento_validacion' => $documentPath,
                ];

                $empresaExistente = DB::table('registro_empresa_producto')
                    ->where('ruc', $data['ruc'])
                    ->orWhere('correo_electronico', $data['correo_electronico'])
                    ->first();

                if ($empresaExistente) {
                    $empresaId = $empresaExistente->id_empresa_producto;
                    DB::table('registro_empresa_producto')->where('id_empresa_producto', $empresaId)->update($empresaData);
                } else {
                    $empresaId = DB::table('registro_empresa_producto')->insertGetId($empresaData);
                }

                DB::table('productos_empresa')->insert([
                    'id_empresa_producto' => $empresaId,
                    'nombre_producto' => $data['nombre_producto'],
                    'descripcion' => $data['descripcion'],
                    'categoria' => $data['categoria'],
                    'ubicacion_ciudad' => $data['ubicacion_ciudad'],
                    'telefono_contacto' => $data['telefono_contacto'],
                    'redes_sociales' => $data['redes_sociales'],
                    'correo_contacto' => $data['correo_contacto'],
                    'direccion_atencion' => $data['direccion_atencion'],
                    'imagen_producto' => $imagePath,
                    'estado' => 'Pendiente',
                    'fecha_inicio' => $fechaInicio->format('Y-m-d'),
                    'fecha_fin' => $fechaFin->format('Y-m-d'),
                ]);
            });
        } catch (QueryException $exception) {
            $this->fileUploadService->deletePublicFile($documentPath);
            $this->fileUploadService->deletePublicFile($imagePath);
            throw $exception;
        }
    }

    public function createService(array $data, ?UploadedFile $document, ?UploadedFile $image, Carbon $fechaInicio, Carbon $fechaFin): void
    {
        $documentPath = null;
        $imagePath = null;

        if ($document) {
            $documentPath = $this->fileUploadService->storePublicFile($document, 'Servicios/documentosServicios');
        }

        if ($image) {
            $imagePath = $this->fileUploadService->storePublicFile($image, 'Servicios/imagenesServicios');
        }

        try {
            DB::transaction(function () use ($data, $documentPath, $imagePath, $fechaInicio, $fechaFin) {
                $empresaData = [
                    'nombre_empresa' => $data['nombre_empresa'],
                    'ruc' => $data['ruc'],
                    'correo_electronico' => $data['correo_electronico'],
                    'telefono' => $data['telefono'],
                    'responsable_representante' => $data['responsable_representante'] ?? null,
                    'direccion' => $data['direccion'] ?? null,
                    'documento_validacion' => $documentPath,
                    'estado' => 'Pendiente',
                    'fecha_registro' => now()->format('Y-m-d'),
                ];

                $empresaExistente = DB::table('registro_empresa_servicio')
                    ->where('ruc', $data['ruc'])
                    ->orWhere('correo_electronico', $data['correo_electronico'])
                    ->first();

                if ($empresaExistente) {
                    $empresaId = $empresaExistente->id_empresa_servicio;
                    DB::table('registro_empresa_servicio')->where('id_empresa_servicio', $empresaId)->update($empresaData);
                } else {
                    $empresaId = DB::table('registro_empresa_servicio')->insertGetId($empresaData);
                }

                DB::table('servicios_empresa')->insert([
                    'id_empresa_servicio' => $empresaId,
                    'nombre_servicio' => $data['nombre_servicio'],
                    'descripcion' => $data['descripcion'],
                    'categoria' => $data['categoria'],
                    'ubicacion_ciudad' => $data['ubicacion_ciudad'] ?? null,
                    'telefono_contacto' => $data['telefono_contacto'] ?? null,
                    'redes_sociales' => $data['redes_sociales'] ?? null,
                    'correo_contacto' => $data['correo_contacto'] ?? null,
                    'direccion_atencion' => $data['direccion_atencion'] ?? null,
                    'imagen_servicio' => $imagePath,
                    'horario_atencion' => $data['horario_atencion'] ?? null,
                    'estado' => 'Pendiente',
                    'fecha_inicio' => $fechaInicio->format('Y-m-d'),
                    'fecha_fin' => $fechaFin->format('Y-m-d'),
                    'fecha_registro' => now()->format('Y-m-d'),
                ]);
            });
        } catch (QueryException $exception) {
            $this->fileUploadService->deletePublicFile($documentPath);
            $this->fileUploadService->deletePublicFile($imagePath);
            throw $exception;
        }
    }

    public function listPublicProducts(): Collection
    {
        return DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '>=', now()->format('Y-m-d'))
            ->orderByDesc('fecha_publicacion')
            ->get();
    }

    public function getPublicProductById(int $id): ?object
    {
        return DB::table('productos_publicos')
            ->where('id_publico_producto', $id)
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '>=', now()->format('Y-m-d'))
            ->first();
    }
}
