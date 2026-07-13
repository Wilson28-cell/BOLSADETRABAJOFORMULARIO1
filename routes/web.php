<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BolsaTrabajoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Admin\BolsaController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\PostulanteController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Analista\IndicatorsController;

Route::get('/test-csrf', fn () => view('test-csrf'));
Route::post('/test-csrf', function () {
    return response()->json(['success' => true, 'message' => 'CSRF token válido']);
});

// Publicidad
Route::get('/', fn () => view('publicidad.porvenir-produce'));
Route::get('/publicidad', fn () => redirect('/publicidad/bolsa-trabajo'));
Route::get('/publicidad/bolsa-trabajo', [BolsaTrabajoController::class, 'publicidadBolsaTrabajo']);
Route::get('/publicidad/productos', [ProductoController::class, 'publicidadProductos']);
Route::get('/publicidad/productos/{id}', [ProductoController::class, 'detalleProducto']);
Route::get('/publicidad/servicios', function (Request $request) {
    $query = DB::table('servicios_publicos')
        ->select('servicios_publicos.*', 'id_publico_servicio as id')
        ->where('estado', 'Publicado')
        ->whereDate('fecha_fin', '>=', now()->format('Y-m-d'));

    $filters = $request->only(['q', 'categoria', 'ubicacion_ciudad']);

    if (!empty($filters['q'])) {
        $q = trim($filters['q']);
        $query->where(function ($sub) use ($q) {
            $sub->where('nombre_servicio', 'like', "%{$q}%")
                ->orWhere('descripcion', 'like', "%{$q}%")
                ->orWhere('nombre_empresa', 'like', "%{$q}%");
        });
    }

    if (!empty($filters['categoria'])) {
        $query->where('categoria', $filters['categoria']);
    }

    if (!empty($filters['ubicacion_ciudad'])) {
        $query->where('ubicacion_ciudad', $filters['ubicacion_ciudad']);
    }

    $servicios = $query->orderByDesc('fecha_publicacion')->get();

    if ($request->ajax()) {
        $html = view('publicidad.partials.servicios-list', compact('servicios'))->render();
        return response()->json(['html' => $html]);
    }

    return view('publicidad.servicios', compact('servicios'));
});

// Autenticación
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'validar'])->name('login.validate');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Registro de empresa
Route::get('/registro', fn () => view('registroempresa.seleccion-publicacion'));
Route::get('/registro-empresa', fn () => redirect('/registro'));
Route::get('/registro/bolsa-trabajo', fn () => view('registroempresa.registro-bolsa-trabajo'));
Route::get('/registro/bolsa-trabajos', fn () => redirect('/registro/bolsa-trabajo'));
Route::get('/registro/productos', fn () => view('registroempresa.registro-empresa-productos'));
Route::get('/registro/servicios', fn () => view('registroempresa.registro-empresa-servicios'));
Route::post('/guardar-bolsa-trabajo', [BolsaTrabajoController::class, 'guardar'])->name('registro.guardar-bolsa-trabajo');
Route::post('/guardar-producto', [ProductoController::class, 'guardar'])->name('registro.guardar-producto');
Route::post('/guardar-servicio', [ProductoController::class, 'guardarServicio'])->name('registro.guardar-servicio');

// Bolsa de trabajo pública
Route::get('/detalle-oferta/{id}', [BolsaTrabajoController::class, 'detalle'])->name('bolsa.detalle');
Route::get('/detalle-servicio/{id}', [BolsaTrabajoController::class, 'detalleServicio'])->name('servicio.detalle');
Route::get('/postular/{id}', [BolsaTrabajoController::class, 'postular'])->name('bolsa.postular');
Route::post('/guardar-postulacion/{id}', [BolsaTrabajoController::class, 'guardarPostulacion'])->name('bolsa.guardar-postulacion');

// Admin
Route::middleware('auth.admin')->prefix('admin')->group(function () {
    Route::get('repair-database', function () {
        try {
            DB::statement('ALTER TABLE `empresas_producto_aprobadas` MODIFY COLUMN `estado` VARCHAR(50) NOT NULL');
            DB::statement('ALTER TABLE `productos_publicos` MODIFY COLUMN `estado` VARCHAR(50) NOT NULL');
            return response()->json([
                'success' => true,
                'message' => 'Base de datos reparada correctamente. Puedes desactivar publicaciones ahora.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    })->name('admin.repair-database');

    Route::get('validacion-formularios', [BolsaController::class, 'validacion']);
    Route::get('rechazados', [BolsaController::class, 'rechazados']);
    Route::get('bolsa-trabajo', [BolsaController::class, 'bolsaTrabajo']);
    Route::get('ver/{id}', [BolsaController::class, 'ver']);
    Route::post('publicaciones/restaurar/{id}', [BolsaController::class, 'restaurarPublicacion']);
    Route::get('publicaciones-desactivadas', [BolsaController::class, 'desactivados']);
    Route::get('publicaciones-desactivadas/{id}', [BolsaController::class, 'verDesactivada']);
    Route::post('eliminar-documento/{id}', [BolsaController::class, 'eliminarDocumento']);
    Route::post('eliminar-publicacion/{id}', [BolsaController::class, 'eliminarPublicacion']);
    Route::post('aprobar/{id}', [DocumentController::class, 'aprobar']);
    Route::post('rechazar/{id}', [DocumentController::class, 'rechazar']);
    Route::post('restaurar/{id}', [DocumentController::class, 'restaurar']);
    Route::post('subir-documento/{id}', [DocumentController::class, 'subirDocumento']);
    Route::post('confirm-password', [AuthController::class, 'confirmPassword']);

    Route::get('indicadores-bolsa', [IndicatorsController::class, 'bolsa']);
    Route::get('indicadores-productos', [IndicatorsController::class, 'productos']);
    Route::get('indicadores-servicios', [IndicatorsController::class, 'servicios']);

    Route::get('postulantes', [PostulanteController::class, 'postulantes']);
    Route::get('postulantes/ver/{id}', [PostulanteController::class, 'verPostulantes']);
    Route::get('postulante/cv/{id}', [PostulanteController::class, 'verCV']);
    Route::get('postulante/visualizar/{id}', [PostulanteController::class, 'visualizarCV']);
    Route::get('postulante/descargar/{id}', [PostulanteController::class, 'descargarCV']);
    Route::get('postulantes/descargar-todos/{id}', [PostulanteController::class, 'descargarTodosCV']);

    Route::get('formularios-productos', [ProductController::class, 'formulariosProductos']);
    Route::get('ver-producto/{id}', [ProductController::class, 'verProducto']);
    Route::get('productos', [ProductController::class, 'productos']);
    Route::get('ver-producto-aprobado/{id}', [ProductController::class, 'verProductoAprobado']);
    Route::post('productos/reactivar/{id}', [ProductController::class, 'reactivarProducto']);
    Route::get('productos-rechazados', [ProductController::class, 'rechazados']);
    Route::post('productos/restaurar/{id}', [ProductController::class, 'restaurar']);
    Route::post('aprobar-producto/{id}', [ProductController::class, 'aprobarProducto']);
    Route::post('rechazar-producto/{id}', [ProductController::class, 'rechazarProducto']);
    Route::delete('eliminar-producto/{id}', [ProductController::class, 'eliminarProducto']);
    Route::delete('borrar-producto/{id}', [ProductController::class, 'borrarProducto']);
    Route::get('desactivar-vencidos', [ProductController::class, 'desactivarVencidos']);

    Route::get('formularios-servicios', [ServiceController::class, 'formulariosServicios']);
    Route::get('ver-servicio/{id}', [ServiceController::class, 'verServicio']);
    
    // Usuarios administrativos (crear Analista / Gestor Operativo)
    Route::get('usuarios/crear', [\App\Http\Controllers\Admin\UserController::class, 'create']);
    Route::post('usuarios', [\App\Http\Controllers\Admin\UserController::class, 'store']);

    Route::post('aprobar-servicio/{id}', [ServiceController::class, 'aprobarServicio']);
    Route::post('rechazar-servicio/{id}', [ServiceController::class, 'rechazarServicio']);
    Route::post('servicios/restaurar/{id}', [ServiceController::class, 'restaurarServicio']);
    Route::get('publicaciones-servicios', [ServiceController::class, 'publicacionesServicios']);
    Route::get('ver-publicacion-servicio/{id}', [ServiceController::class, 'verPublicacionServicio']);
    Route::post('publicaciones-servicios/desactivar/{id}', [ServiceController::class, 'desactivarPublicacionServicio']);
    Route::post('publicaciones-servicios/reactivar/{id}', [ServiceController::class, 'reactivarPublicacionServicio']);
    Route::delete('publicaciones-servicios/borrar/{id}', [ServiceController::class, 'borrarPublicacionServicio']);
    Route::get('ver-servicio-rechazado/{id}', [ServiceController::class, 'verServicioRechazado']);
    Route::get('servicios-rechazados', [ServiceController::class, 'rechazados']);
});
