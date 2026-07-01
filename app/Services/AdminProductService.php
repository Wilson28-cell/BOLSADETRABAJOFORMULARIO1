<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminProductService
{
    public function __construct(private FileUploadService $fileUploadService)
    {
    }

    public function listPendingProducts()
    {
        return DB::table('registro_empresa_producto as e')
            ->join('productos_empresa as p', 'e.id_empresa_producto', '=', 'p.id_empresa_producto')
            ->select(
                'e.*',
                'p.id_producto',
                'p.nombre_producto',
                'p.descripcion',
                'p.categoria',
                'p.ubicacion_ciudad',
                'p.telefono_contacto',
                'p.redes_sociales',
                'p.correo_contacto',
                'p.direccion_atencion',
                'p.imagen_producto',
                'p.estado as estado_producto',
                'p.fecha_inicio',
                'p.fecha_fin'
            )
            ->get();
    }

    public function listApprovedProducts(array $filters = [])
    {
        $query = DB::table('empresas_producto_aprobadas');

        if (!empty($filters['buscar'])) {
            $buscar = $filters['buscar'];
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_empresa', 'like', '%' . $buscar . '%')
                    ->orWhere('nombre_producto', 'like', '%' . $buscar . '%');
            });
        }

        if (!empty($filters['estado']) && $filters['estado'] !== 'todos') {
            $query->where('estado', $filters['estado']);
        }

        if (!empty($filters['fecha_inicio'])) {
            $query->whereDate('fecha_inicio', '>=', $filters['fecha_inicio']);
        }

        if (!empty($filters['fecha_fin'])) {
            $query->whereDate('fecha_fin', '<=', $filters['fecha_fin']);
        }

        return $query->orderBy('fecha_aprobacion', 'desc')->get();
    }

    public function listRejectedProducts()
    {
        if (Schema::hasTable('empresas_producto_rechazadas')) {
            return DB::table('empresas_producto_rechazadas')->orderBy('fecha_rechazo', 'desc')->get();
        }

        if (Schema::hasTable('empresas_bolsadetrabajo_rechazadas')) {
            return DB::table('empresas_bolsadetrabajo_rechazadas')
                ->select(
                    'id_rechazado',
                    'nombre_empresa',
                    'ruc',
                    'titulo_puesto as nombre_producto',
                    'categoria',
                    'ubicacion as ubicacion_ciudad',
                    'documento_validacion',
                    'fecha_rechazo'
                )
                ->orderBy('fecha_rechazo', 'desc')
                ->get();
        }

        return DB::table('registro_empresa_producto as e')
            ->join('productos_empresa as p', 'e.id_empresa_producto', '=', 'p.id_empresa_producto')
            ->where('p.estado', 'Rechazado')
            ->select('e.*', 'p.*')
            ->get();
    }

    public function getPendingProduct(int $id)
    {
        return DB::table('registro_empresa_producto as e')
            ->join('productos_empresa as p', 'e.id_empresa_producto', '=', 'p.id_empresa_producto')
            ->where('e.id_empresa_producto', $id)
            ->select(
                'e.*',
                'p.id_producto',
                'p.nombre_producto',
                'p.descripcion',
                'p.categoria',
                'p.ubicacion_ciudad',
                'p.telefono_contacto',
                'p.redes_sociales',
                'p.correo_contacto',
                'p.direccion_atencion',
                'p.imagen_producto',
                'p.estado as estado_producto',
                'p.fecha_inicio',
                'p.fecha_fin'
            )
            ->first();
    }

    public function getApprovedProduct(int $id)
    {
        return DB::table('empresas_producto_aprobadas')->where('id_aprobado', $id)->first();
    }

    public function approveProduct(int $id, ?UploadedFile $documentoSubgerencia = null): void
    {
        $producto = $this->getPendingProduct($id);
        if (!$producto) {
            throw new \RuntimeException('Producto pendiente no encontrado.');
        }

        $documentoSubgerenciaPath = null;
        if ($documentoSubgerencia) {
            $documentoSubgerenciaPath = $this->fileUploadService->storePublicFile(
                $documentoSubgerencia,
                'Productos/documentosProductosAprobadosSubgerencia'
            );
        }

        $nuevoDocumento = $this->fileUploadService->copyPublicFile(
            $producto->documento_validacion,
            'Productos/documentosProductosAprobados'
        ) ?? $producto->documento_validacion;

        $nuevaImagen = $producto->imagen_producto ?: '';
        $imagenAprobada = $this->fileUploadService->copyPublicFile(
            $producto->imagen_producto,
            'Productos/imagenesProductosAprobados'
        );

        if ($imagenAprobada) {
            $nuevaImagen = $imagenAprobada;
        }

        DB::transaction(function () use ($producto, $nuevoDocumento, $nuevaImagen, $documentoSubgerenciaPath, $id) {
            $idAprobado = DB::table('empresas_producto_aprobadas')->insertGetId([
                'id_empresa_original' => $producto->id_empresa_producto,
                'id_producto_original' => $producto->id_producto,
                'id_usuario_aprobador' => 1,
                'nombre_empresa' => $producto->nombre_empresa,
                'ruc' => $producto->ruc,
                'correo_electronico' => $producto->correo_electronico,
                'telefono' => $producto->telefono,
                'responsable_representante' => $producto->responsable_representante,
                'direccion' => $producto->direccion,
                'documento_validacion' => $nuevoDocumento,
                'documento_aprobacion_pdf' => $documentoSubgerenciaPath ?: '',
                'nombre_producto' => $producto->nombre_producto,
                'descripcion' => $producto->descripcion,
                'categoria' => $producto->categoria,
                'ubicacion_ciudad' => $producto->ubicacion_ciudad,
                'telefono_contacto' => $producto->telefono_contacto,
                'redes_sociales' => $producto->redes_sociales,
                'correo_contacto' => $producto->correo_contacto,
                'direccion_atencion' => $producto->direccion_atencion,
                'imagen_producto' => $nuevaImagen,
                'fecha_inicio' => $producto->fecha_inicio,
                'fecha_fin' => $producto->fecha_fin,
                'estado' => 'Publicado',
                'fecha_aprobacion' => now(),
            ]);

            DB::table('productos_publicos')->insert([
                'id_aprobado' => $idAprobado,
                'nombre_empresa' => $producto->nombre_empresa,
                'nombre_producto' => $producto->nombre_producto,
                'descripcion' => $producto->descripcion,
                'categoria' => $producto->categoria,
                'ubicacion_ciudad' => $producto->ubicacion_ciudad,
                'telefono_contacto' => $producto->telefono_contacto,
                'redes_sociales' => $producto->redes_sociales,
                'correo_contacto' => $producto->correo_contacto,
                'direccion_atencion' => $producto->direccion_atencion,
                'imagen_producto' => $nuevaImagen,
                'estado' => 'Publicado',
                'fecha_inicio' => $producto->fecha_inicio,
                'fecha_fin' => $producto->fecha_fin,
                'fecha_publicacion' => now(),
            ]);

            DB::table('productos_empresa')->where('id_empresa_producto', $id)->delete();
            DB::table('registro_empresa_producto')->where('id_empresa_producto', $id)->delete();
        });
    }

    public function rejectProduct(int $id): void
    {
        $producto = $this->getPendingProduct($id);
        if (!$producto) {
            throw new \RuntimeException('Producto pendiente no encontrado.');
        }

        DB::transaction(function () use ($producto) {
            if (Schema::hasTable('empresas_producto_rechazadas')) {
                $rechazadoData = [
                    'id_empresa_original' => $producto->id_empresa_producto,
                    'id_producto_original' => $producto->id_producto,
                    'nombre_empresa' => $producto->nombre_empresa,
                    'ruc' => $producto->ruc,
                    'correo_electronico' => $producto->correo_electronico,
                    'telefono' => $producto->telefono,
                    'responsable_representante' => $producto->responsable_representante,
                    'direccion' => $producto->direccion,
                    'documento_validacion' => $producto->documento_validacion,
                    'nombre_producto' => $producto->nombre_producto,
                    'descripcion' => $producto->descripcion,
                    'categoria' => $producto->categoria,
                    'ubicacion_ciudad' => $producto->ubicacion_ciudad,
                    'telefono_contacto' => $producto->telefono_contacto,
                    'redes_sociales' => $producto->redes_sociales,
                    'correo_contacto' => $producto->correo_contacto,
                    'direccion_atencion' => $producto->direccion_atencion,
                    'imagen_producto' => $producto->imagen_producto,
                    'fecha_inicio' => $producto->fecha_inicio,
                    'fecha_fin' => $producto->fecha_fin,
                    'fecha_rechazo' => now(),
                ];

                if (Schema::hasColumn('empresas_producto_rechazadas', 'id_usuario_rechazo')) {
                    $rechazadoData['id_usuario_rechazo'] = 1;
                }

                if (Schema::hasColumn('empresas_producto_rechazadas', 'motivo_rechazo')) {
                    $rechazadoData['motivo_rechazo'] = '';
                }

                if (Schema::hasColumn('empresas_producto_rechazadas', 'created_at')) {
                    $rechazadoData['created_at'] = now();
                }

                if (Schema::hasColumn('empresas_producto_rechazadas', 'updated_at')) {
                    $rechazadoData['updated_at'] = now();
                }

                DB::table('empresas_producto_rechazadas')->insert($rechazadoData);
            } else {
                $rechazadoData = [
                    'id_empresa_original' => $producto->id_empresa_producto,
                    'id_publicacion_original' => $producto->id_producto,
                    'id_usuario_rechazador' => 1,
                    'nombre_empresa' => $producto->nombre_empresa,
                    'ruc' => $producto->ruc,
                    'correo_electronico' => $producto->correo_electronico,
                    'telefono' => $producto->telefono,
                    'responsable_representante' => $producto->responsable_representante,
                    'direccion' => $producto->direccion,
                    'documento_validacion' => $producto->documento_validacion,
                    'titulo_puesto' => $producto->nombre_producto,
                    'descripcion_puesto' => $producto->descripcion,
                    'requisitos' => null,
                    'imagen_trabajo' => $producto->imagen_producto,
                    'modalidad' => 'Producto',
                    'categoria' => $producto->categoria,
                    'salario' => null,
                    'ubicacion' => $producto->ubicacion_ciudad,
                    'fecha_inicio_convocatoria' => $producto->fecha_inicio,
                    'fecha_limite_postulacion' => $producto->fecha_fin,
                    'veces_restaurado' => 0,
                    'fecha_rechazo' => now(),
                ];

                if (Schema::hasColumn('empresas_bolsadetrabajo_rechazadas', 'created_at')) {
                    $rechazadoData['created_at'] = now();
                }

                if (Schema::hasColumn('empresas_bolsadetrabajo_rechazadas', 'updated_at')) {
                    $rechazadoData['updated_at'] = now();
                }

                DB::table('empresas_bolsadetrabajo_rechazadas')->insert($rechazadoData);
            }

            DB::table('productos_empresa')->where('id_empresa_producto', $producto->id_empresa_producto)->delete();
            DB::table('registro_empresa_producto')->where('id_empresa_producto', $producto->id_empresa_producto)->delete();
        });
    }

    public function restoreProduct(int $id): void
    {
        if (Schema::hasTable('empresas_producto_rechazadas')) {
            $producto = DB::table('empresas_producto_rechazadas')->where('id_rechazado', $id)->first();

            if (!$producto) {
                throw new \RuntimeException('Producto rechazado no encontrado.');
            }

            DB::transaction(function () use ($producto, $id) {
                $insertData = [
                    'nombre_empresa' => $producto->nombre_empresa,
                    'ruc' => $producto->ruc,
                    'correo_electronico' => $producto->correo_electronico,
                    'telefono' => $producto->telefono,
                    'responsable_representante' => $producto->responsable_representante,
                    'direccion' => $producto->direccion,
                    'documento_validacion' => $producto->documento_validacion,
                ];

                $empresaId = DB::table('registro_empresa_producto')->insertGetId($insertData);

                $productoData = [
                    'id_empresa_producto' => $empresaId,
                    'nombre_producto' => $producto->nombre_producto,
                    'descripcion' => $producto->descripcion,
                    'categoria' => $producto->categoria,
                    'ubicacion_ciudad' => $producto->ubicacion_ciudad,
                    'telefono_contacto' => $producto->telefono_contacto,
                    'redes_sociales' => $producto->redes_sociales,
                    'correo_contacto' => $producto->correo_contacto,
                    'direccion_atencion' => $producto->direccion_atencion,
                    'imagen_producto' => $producto->imagen_producto,
                    'estado' => 'Pendiente',
                    'fecha_inicio' => $producto->fecha_inicio,
                    'fecha_fin' => $producto->fecha_fin,
                ];

                if (Schema::hasColumn('productos_empresa', 'created_at')) {
                    $productoData['created_at'] = now();
                }

                if (Schema::hasColumn('productos_empresa', 'updated_at')) {
                    $productoData['updated_at'] = now();
                }

                DB::table('productos_empresa')->insert($productoData);
                DB::table('empresas_producto_rechazadas')->where('id_rechazado', $id)->delete();
            });

            return;
        }

        if (Schema::hasTable('empresas_bolsadetrabajo_rechazadas')) {
            $producto = DB::table('empresas_bolsadetrabajo_rechazadas')->where('id_rechazado', $id)->first();

            if (!$producto) {
                throw new \RuntimeException('Producto rechazado no encontrado.');
            }

            DB::transaction(function () use ($producto, $id) {
                $insertData = [
                    'nombre_empresa' => $producto->nombre_empresa,
                    'ruc' => $producto->ruc,
                    'correo_electronico' => $producto->correo_electronico,
                    'telefono' => $producto->telefono,
                    'responsable_representante' => $producto->responsable_representante,
                    'direccion' => $producto->direccion,
                    'documento_validacion' => $producto->documento_validacion,
                ];

                $empresaId = DB::table('registro_empresa_producto')->insertGetId($insertData);

                $productoData = [
                    'id_empresa_producto' => $empresaId,
                    'nombre_producto' => $producto->titulo_puesto,
                    'descripcion' => $producto->descripcion_puesto,
                    'categoria' => $producto->categoria,
                    'ubicacion_ciudad' => $producto->ubicacion,
                    'telefono_contacto' => $producto->telefono,
                    'redes_sociales' => null,
                    'correo_contacto' => $producto->correo_electronico,
                    'direccion_atencion' => $producto->direccion,
                    'imagen_producto' => $producto->imagen_trabajo,
                    'estado' => 'Pendiente',
                    'fecha_inicio' => $producto->fecha_inicio_convocatoria,
                    'fecha_fin' => $producto->fecha_limite_postulacion,
                ];

                if (Schema::hasColumn('productos_empresa', 'created_at')) {
                    $productoData['created_at'] = now();
                }

                if (Schema::hasColumn('productos_empresa', 'updated_at')) {
                    $productoData['updated_at'] = now();
                }

                DB::table('productos_empresa')->insert($productoData);
                DB::table('empresas_bolsadetrabajo_rechazadas')->where('id_rechazado', $id)->delete();
            });

            return;
        }

        throw new \RuntimeException('No se puede restaurar este producto.');
    }

    public function deactivateProduct(int $id): void
    {
        $producto = DB::table('empresas_producto_aprobadas')->where('id_aprobado', $id)->first();

        if (!$producto) {
            throw new \RuntimeException('Publicación no encontrada.');
        }

        DB::transaction(function () use ($producto, $id) {
            DB::table('empresas_producto_aprobadas')
                ->where('id_aprobado', $id)
                ->update(['estado' => 'Desactivado']);

            DB::table('productos_publicos')
                ->whereRaw('LOWER(nombre_producto) = LOWER(?) AND LOWER(nombre_empresa) = LOWER(?)', [
                    $producto->nombre_producto,
                    $producto->nombre_empresa ?? '',
                ])
                ->update(['estado' => 'Desactivado']);
        });
    }

    public function deleteProduct(int $id): void
    {
        $producto = DB::table('empresas_producto_aprobadas')->where('id_aprobado', $id)->first();

        if (!$producto) {
            throw new \RuntimeException('Publicación no encontrada.');
        }

        DB::transaction(function () use ($producto, $id) {
            DB::table('productos_publicos')
                ->whereRaw('LOWER(nombre_producto) = LOWER(?) AND LOWER(nombre_empresa) = LOWER(?)', [
                    $producto->nombre_producto,
                    $producto->nombre_empresa ?? '',
                ])
                ->delete();

            DB::table('empresas_producto_aprobadas')
                ->where('id_aprobado', $id)
                ->delete();
        });
    }

    public function reactivateProduct(int $id): void
    {
        $producto = DB::table('empresas_producto_aprobadas')->where('id_aprobado', $id)->first();

        if (!$producto) {
            throw new \RuntimeException('Publicación no encontrada.');
        }

        DB::transaction(function () use ($producto, $id) {
            DB::table('empresas_producto_aprobadas')
                ->where('id_aprobado', $id)
                ->update(['estado' => 'Publicado']);

            DB::table('productos_publicos')
                ->whereRaw('LOWER(nombre_producto) = LOWER(?) AND LOWER(nombre_empresa) = LOWER(?)', [
                    $producto->nombre_producto,
                    $producto->nombre_empresa ?? '',
                ])
                ->update(['estado' => 'Publicado']);
        });
    }

    public function deactivateExpired(): void
    {
        $hoy = Carbon::now()->startOfDay();

        DB::table('empresas_producto_aprobadas')
            ->where('fecha_fin', '<', $hoy)
            ->where('estado', '!=', 'Desactivado')
            ->update(['estado' => 'Desactivado']);

        DB::table('productos_publicos')
            ->where('fecha_fin', '<', $hoy)
            ->where('estado', '!=', 'Desactivado')
            ->update(['estado' => 'Desactivado']);
    }
}
