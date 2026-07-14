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
        
        // Agregar 10 productos de demostración sin guardar en BD
        $productosDemo = collect([
            (object)[
                'id_publico_producto' => 999001,
                'nombre_producto' => 'Zapato Ejecutivo Negativo',
                'nombre_empresa' => 'CalzadosPro SA',
                'descripcion' => 'Zapato ejecutivo cómodo para trabajo de oficina, fabricado con cuero sintético premium.',
                'categoria' => 'Calzado',
                'ubicacion_ciudad' => 'Lima',
                'precio' => 189.90,
                'correo_contacto' => 'ventas@calzadospro.com',
                'imagen_producto' => 'Productos/imagenesProductosAprobados/1781577228_1-zapato-ejecutivo.jpg',
                'fecha_fin' => now()->addDays(30)->format('Y-m-d'),
                'fecha_publicacion' => now()->subDays(5)->format('Y-m-d'),
                'estado' => 'Publicado',
            ],
            (object)[
                'id_publico_producto' => 999002,
                'nombre_producto' => 'Botas de Seguridad Industrial',
                'nombre_empresa' => 'SeguroTrabajo Ltda',
                'descripcion' => 'Botas de seguridad con puntera de acero, resistentes a impactos y derrames químicos.',
                'categoria' => 'Equipo de Protección',
                'ubicacion_ciudad' => 'Arequipa',
                'precio' => 245.50,
                'correo_contacto' => 'contacto@segurotrabajo.pe',
                'imagen_producto' => 'Productos/imagenesProductosAprobados/1781673520_3-botas-de-seguridad.jpg',
                'fecha_fin' => now()->addDays(35)->format('Y-m-d'),
                'fecha_publicacion' => now()->subDays(3)->format('Y-m-d'),
                'estado' => 'Publicado',
            ],
            (object)[
                'id_publico_producto' => 999003,
                'nombre_producto' => 'Taladro Percutor Profesional',
                'nombre_empresa' => 'HerramientasPlus SRL',
                'descripcion' => 'Taladro percutor de 650W, ideal para construcción y trabajos en concreto.',
                'categoria' => 'Herramientas',
                'ubicacion_ciudad' => 'Lima',
                'precio' => 425.00,
                'correo_contacto' => 'ventas@herramientasplus.com',
                'imagen_producto' => 'Productos/imagenesProductosAprobados/1781737502_4-taladro-percutor.jpg',
                'fecha_fin' => now()->addDays(40)->format('Y-m-d'),
                'fecha_publicacion' => now()->subDays(7)->format('Y-m-d'),
                'estado' => 'Publicado',
            ],
            (object)[
                'id_publico_producto' => 999004,
                'nombre_producto' => 'Empaque de Cartón Corrugado',
                'nombre_empresa' => 'EmpaqueSoluciones SA',
                'descripcion' => 'Cajas de cartón corrugado 40x30x20cm, resistentes para envíos seguros.',
                'categoria' => 'Empaque',
                'ubicacion_ciudad' => 'Callao',
                'precio' => 2.50,
                'correo_contacto' => 'pedidos@empaquesol.com',
                'imagen_producto' => 'Productos/imagenesProductosAprobados/1782346365_5-empaque-carton.jpg',
                'fecha_fin' => now()->addDays(25)->format('Y-m-d'),
                'fecha_publicacion' => now()->subDays(2)->format('Y-m-d'),
                'estado' => 'Publicado',
            ],
            (object)[
                'id_publico_producto' => 999005,
                'nombre_producto' => 'Uniforme Administrativo Conjunto',
                'nombre_empresa' => 'UniformesModernos SA',
                'descripcion' => 'Conjunto de uniforme administrativo (pantalón + camisa), tela de algodón resistente.',
                'categoria' => 'Indumentaria',
                'ubicacion_ciudad' => 'Lima',
                'precio' => 89.99,
                'correo_contacto' => 'info@uniformesmodernos.com',
                'imagen_producto' => 'Productos/imagenesProductosAprobados/1781587258_8-analista-logistica.jpg',
                'fecha_fin' => now()->addDays(28)->format('Y-m-d'),
                'fecha_publicacion' => now()->subDays(4)->format('Y-m-d'),
                'estado' => 'Publicado',
            ],
            (object)[
                'id_publico_producto' => 999006,
                'nombre_producto' => 'Escritorio Ejecutivo 1.5m',
                'nombre_empresa' => 'MueblesOficina Pro',
                'descripcion' => 'Escritorio ejecutivo fabricado en MDF resistente, ideal para espacios de trabajo moderno.',
                'categoria' => 'Muebles',
                'ubicacion_ciudad' => 'Trujillo',
                'precio' => 599.00,
                'correo_contacto' => 'ventas@mueblespro.pe',
                'imagen_producto' => 'Productos/imagenesProductosAprobados/1781682549_9-asistente-admin.jpg',
                'fecha_fin' => now()->addDays(45)->format('Y-m-d'),
                'fecha_publicacion' => now()->subDays(6)->format('Y-m-d'),
                'estado' => 'Publicado',
            ],
            (object)[
                'id_publico_producto' => 999007,
                'nombre_producto' => 'Manillas Reflectivas para Seguridad',
                'nombre_empresa' => 'SeguroEquipo SA',
                'descripcion' => 'Manillas reflectivas de alta visibilidad para seguridad en trabajos nocturnos.',
                'categoria' => 'Equipo de Protección',
                'ubicacion_ciudad' => 'Cusco',
                'precio' => 12.50,
                'correo_contacto' => 'info@seguroequipo.com',
                'imagen_producto' => 'Productos/imagenesProductosAprobados/1781577228_1-zapato-ejecutivo.jpg',
                'fecha_fin' => now()->addDays(32)->format('Y-m-d'),
                'fecha_publicacion' => now()->subDays(1)->format('Y-m-d'),
                'estado' => 'Publicado',
            ],
            (object)[
                'id_publico_producto' => 999008,
                'nombre_producto' => 'Compresor de Aire Portátil',
                'nombre_empresa' => 'HerramientasPlus SRL',
                'descripcion' => 'Compresor de aire portátil de 100L, perfecto para talleres y construcción.',
                'categoria' => 'Herramientas',
                'ubicacion_ciudad' => 'Lima',
                'precio' => 850.00,
                'correo_contacto' => 'ventas@herramientasplus.com',
                'imagen_producto' => 'Productos/imagenesProductosAprobados/1781737502_4-taladro-percutor.jpg',
                'fecha_fin' => now()->addDays(38)->format('Y-m-d'),
                'fecha_publicacion' => now()->subDays(8)->format('Y-m-d'),
                'estado' => 'Publicado',
            ],
            (object)[
                'id_publico_producto' => 999009,
                'nombre_producto' => 'Casco de Seguridad Amarillo',
                'nombre_empresa' => 'SeguroTrabajo Ltda',
                'descripcion' => 'Casco de seguridad homologado, con arnés ajustable y ventilación superior.',
                'categoria' => 'Equipo de Protección',
                'ubicacion_ciudad' => 'Piura',
                'precio' => 45.90,
                'correo_contacto' => 'contacto@segurotrabajo.pe',
                'imagen_producto' => 'Productos/imagenesProductosAprobados/1781673520_3-botas-de-seguridad.jpg',
                'fecha_fin' => now()->addDays(27)->format('Y-m-d'),
                'fecha_publicacion' => now()->subDays(9)->format('Y-m-d'),
                'estado' => 'Publicado',
            ],
            (object)[
                'id_publico_producto' => 999010,
                'nombre_producto' => 'Estante de Almacenamiento Metálico',
                'nombre_empresa' => 'MueblesOficina Pro',
                'descripcion' => 'Estante metálico de 5 niveles, capacidad de carga 200kg por nivel, ideal para almacenes.',
                'categoria' => 'Muebles',
                'ubicacion_ciudad' => 'Lima',
                'precio' => 350.00,
                'correo_contacto' => 'ventas@mueblespro.pe',
                'imagen_producto' => 'Productos/imagenesProductosAprobados/1782346365_5-empaque-carton.jpg',
                'fecha_fin' => now()->addDays(42)->format('Y-m-d'),
                'fecha_publicacion' => now()->subDays(10)->format('Y-m-d'),
                'estado' => 'Publicado',
            ],
        ]);
        
        // Aplicar filtros a los productos demo
        if (!empty($filters['q'])) {
            $q = trim($filters['q']);
            $productosDemo = $productosDemo->filter(function ($producto) use ($q) {
                return stripos($producto->nombre_producto, $q) !== false ||
                       stripos($producto->descripcion, $q) !== false ||
                       stripos($producto->nombre_empresa, $q) !== false;
            });
        }

        if (!empty($filters['categoria'])) {
            $productosDemo = $productosDemo->filter(function ($producto) use ($filters) {
                return $producto->categoria === $filters['categoria'];
            });
        }

        if (!empty($filters['ubicacion_ciudad'])) {
            $productosDemo = $productosDemo->filter(function ($producto) use ($filters) {
                return $producto->ubicacion_ciudad === $filters['ubicacion_ciudad'];
            });
        }
        
        // Combinar productos de BD con productos filtrados de demostración
        $productos = $productos->concat($productosDemo);

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
