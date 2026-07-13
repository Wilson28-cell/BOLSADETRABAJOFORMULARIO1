<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreServiceRequest;
use App\UseCases\Public\ProductoService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct(private ProductoService $productoService)
    {
    }

    // =====================================================
    // GUARDAR PRODUCTO
    // =====================================================

    public function guardar(StoreProductRequest $request)
    {
        if (!$request->hasFile('imagen_producto')) {
            return back()->withInput()->withErrors([
                'imagen_producto' => 'No se detectó la imagen. Asegúrate de seleccionar un archivo válido y que no supere el límite de subida en el servidor.',
            ]);
        }

        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)
            : Carbon::today();

        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)
            : $fechaInicio->copy()->addMonth();

        $maxFin = $fechaInicio->copy()->addYear();

        if ($fechaFin->gt($maxFin)) {
            return back()->withInput()->withErrors(['fecha_fin' => 'La fecha de fin no puede ser mayor a un año desde la fecha de inicio.']);
        }

        try {
            // Log file info for diagnostics
            try {
                $doc = $request->file('documento_validacion');
                $img = $request->file('imagen_producto');
                \Log::info('Producto upload files info', [
                    'documento' => $doc ? [
                        'clientName' => $doc->getClientOriginalName(),
                        'tmpPath' => $doc->getPathname(),
                        'isValid' => method_exists($doc, 'isValid') ? $doc->isValid() : null,
                        'size' => $doc->getSize(),
                    ] : null,
                    'imagen' => $img ? [
                        'clientName' => $img->getClientOriginalName(),
                        'tmpPath' => $img->getPathname(),
                        'isValid' => method_exists($img, 'isValid') ? $img->isValid() : null,
                        'size' => $img->getSize(),
                    ] : null,
                ]);
            } catch (\Exception $e) {
                \Log::warning('No se pudo leer metadata de archivos subidos', ['error' => $e->getMessage()]);
            }

            $this->productoService->createProduct(
                $request->validated(),
                $request->file('documento_validacion'),
                $request->file('imagen_producto'),
                $fechaInicio,
                $fechaFin
            );
        } catch (\Illuminate\Database\QueryException $exception) {
            \Log::error('QueryException al guardar producto', ['error' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['database' => 'Error al guardar el producto.'])->with('error', 'Error en el servidor al guardar el producto.');
        } catch (\Exception $exception) {
            \Log::error('Exception al guardar producto', ['error' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]);
            return back()->withInput()->withErrors(['upload' => 'Error al procesar los archivos subidos: ' . $exception->getMessage()])->with('error', 'Error al procesar los archivos subidos. Revisa logs.');
        }

        return redirect()->back()->with('success', 'Producto registrado correctamente');
    }

    // =====================================================
    // GUARDAR SERVICIO
    // =====================================================

    public function guardarServicio(StoreServiceRequest $request)
    {
        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)
            : Carbon::today();

        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)
            : $fechaInicio->copy()->addMonth();

        $maxFin = $fechaInicio->copy()->addYear();
        if ($fechaFin->gt($maxFin)) {
            return back()->withInput()->withErrors(['fecha_fin' => 'La fecha de fin no puede ser mayor a un año desde la fecha de inicio.']);
        }

        try {
            try {
                $doc = $request->file('documento_validacion');
                $img = $request->file('imagen_servicio');
                \Log::info('Servicio upload files info', [
                    'documento' => $doc ? [
                        'clientName' => $doc->getClientOriginalName(),
                        'tmpPath' => $doc->getPathname(),
                        'isValid' => method_exists($doc, 'isValid') ? $doc->isValid() : null,
                        'size' => $doc->getSize(),
                    ] : null,
                    'imagen' => $img ? [
                        'clientName' => $img->getClientOriginalName(),
                        'tmpPath' => $img->getPathname(),
                        'isValid' => method_exists($img, 'isValid') ? $img->isValid() : null,
                        'size' => $img->getSize(),
                    ] : null,
                ]);
            } catch (\Exception $e) {
                \Log::warning('No se pudo leer metadata de archivos subidos (servicio)', ['error' => $e->getMessage()]);
            }

            $this->productoService->createService(
                $request->validated(),
                $request->file('documento_validacion'),
                $request->file('imagen_servicio'),
                $fechaInicio,
                $fechaFin
            );
        } catch (\Illuminate\Database\QueryException $exception) {
            \Log::error('QueryException al guardar servicio', ['error' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['database' => 'Error al guardar el servicio.'])->with('error', 'Error en el servidor al guardar el servicio.');
        } catch (\Exception $exception) {
            \Log::error('Exception al guardar servicio', ['error' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]);
            return back()->withInput()->withErrors(['upload' => 'Error al procesar los archivos subidos: ' . $exception->getMessage()])->with('error', 'Error al procesar los archivos subidos. Revisa logs.');
        }

        return redirect()->back()->with('success', 'Servicio registrado correctamente');
    }

    // =====================================================
    // PUBLICIDAD PRODUCTOS
    // =====================================================

    public function publicidadProductos(Request $request)
    {
        $filters = $request->only(['q', 'categoria', 'ubicacion_ciudad']);

        $productos = $this->productoService->listPublicProducts($filters);

        if ($request->ajax()) {
            $html = view('publicidad.partials.productos-list', compact('productos'))->render();
            return response()->json(['html' => $html]);
        }

        return view('publicidad.productos', compact('productos'));
    }

    public function detalleProducto(int $id)
    {
        $producto = $this->productoService->getPublicProductById($id);

        if (!$producto) {
            abort(404);
        }

        return view('publicidad.producto-detalle', compact('producto'));
    }
}
