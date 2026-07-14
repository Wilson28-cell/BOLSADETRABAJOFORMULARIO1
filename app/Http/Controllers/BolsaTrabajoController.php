<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBolsaTrabajoRequest;
use App\Http\Requests\StorePostulacionRequest;
use App\UseCases\Public\BolsaTrabajoService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BolsaTrabajoController extends Controller
{
    private array $categoriasPermitidas = [
        'Tecnología y Desarrollo',
        'Administración y Negocios',
        'Ventas y Marketing',
        'Salud y Medicina',
        'Finanzas y Contabilidad',
        'Educación y Formación',
        'Ingeniería y Construcción',
        'Hostelería y Turismo',
        'Logística, Transporte y Almacén',
        'Servicios de Seguridad y Mantenimiento',
    ];

    public function __construct(private BolsaTrabajoService $bolsaTrabajoService)
    {
    }

    private function categoriasDisponibles(): \Illuminate\Support\Collection
    {
        return collect($this->categoriasPermitidas);
    }

    public function guardar(StoreBolsaTrabajoRequest $request)
    {
        try {
            $this->bolsaTrabajoService->createPublication(
                $request->validated(),
                $request->file('documento_validacion'),
                $request->file('imagen_trabajo')
            );
        } catch (\Illuminate\Database\QueryException $exception) {
            $code = $exception->errorInfo[1] ?? null;
            if ($code === 1062) {
                return back()->withInput()->withErrors(['ruc' => 'El RUC ya está registrado.'])->with('error', 'Error al guardar: datos duplicados.');
            }

            return back()->withInput()->withErrors(['database' => 'Error al guardar la empresa.'])->with('error', 'Error al guardar la publicación.');
        }

        return back()->with('success', 'Publicación registrada correctamente. La aprobación puede demorar 1-2 días hábiles.');
    }

    public function inicio(Request $request)
    {
        $filters = $request->only(['buscar', 'modalidad', 'categoria']);
        $filters['categoria'] = in_array($filters['categoria'] ?? null, $this->categoriasPermitidas, true)
            ? $filters['categoria']
            : null;

        $ofertas = $this->bolsaTrabajoService->listPublicJobs($filters);
        $categorias = $this->categoriasDisponibles();

        return view('bolsa-trabajo.index', compact('ofertas', 'categorias'));
    }

    public function publicidadBolsaTrabajo(Request $request)
    {
        $filters = $request->only(['buscar', 'modalidad', 'categoria', 'salario_min', 'estado']);
        $filters['estado'] = in_array($filters['estado'] ?? null, ['publicadas', 'vencidas'], true)
            ? $filters['estado']
            : 'publicadas';
        $filters['categoria'] = in_array($filters['categoria'] ?? null, $this->categoriasPermitidas, true)
            ? $filters['categoria']
            : null;

        $ofertas = $this->bolsaTrabajoService->listPublicJobs($filters);
        
        // Agregar 10 ofertas de demostración sin guardar en BD
        $ofertasDemo = collect([
            (object)[
                'id_aprobado' => 999001,
                'titulo_puesto' => 'Desarrollador Full Stack',
                'nombre_empresa' => 'TechSolutions Inc',
                'descripcion_puesto' => 'Buscamos un desarrollador con experiencia en Laravel y Vue.js para proyectos web.',
                'modalidad' => 'Remoto',
                'categoria' => 'Tecnología y Desarrollo',
                'salario' => 3500.00,
                'ubicacion' => 'Lima',
                'fecha_limite_postulacion' => now()->addDays(15)->format('Y-m-d H:i:s'),
                'fecha_publicacion_publica' => now()->subDays(2)->format('Y-m-d H:i:s'),
                'imagen_trabajo' => 'estatico/bolsa/desarrollador full stack.jpg',
            ],
            (object)[
                'id_aprobado' => 999002,
                'titulo_puesto' => 'Asistente de Recursos Humanos',
                'nombre_empresa' => 'GrupoEmpresarial SA',
                'descripcion_puesto' => 'Apoyo en selección, nomina y gestión de personal para empresas medianas.',
                'modalidad' => 'Presencial',
                'categoria' => 'Administración y Negocios',
                'salario' => 2000.00,
                'ubicacion' => 'Arequipa',
                'fecha_limite_postulacion' => now()->addDays(20)->format('Y-m-d H:i:s'),
                'fecha_publicacion_publica' => now()->subDays(1)->format('Y-m-d H:i:s'),
                'imagen_trabajo' => 'estatico/bolsa/recursos humanos.jpg',
            ],
            (object)[
                'id_aprobado' => 999003,
                'titulo_puesto' => 'Especialista en Marketing Digital',
                'nombre_empresa' => 'Creative Agency Plus',
                'descripcion_puesto' => 'Gestión de campañas, redes sociales y posicionamiento SEO para clientes variados.',
                'modalidad' => 'Híbrido',
                'categoria' => 'Ventas y Marketing',
                'salario' => 2800.00,
                'ubicacion' => 'Lima',
                'fecha_limite_postulacion' => now()->addDays(18)->format('Y-m-d H:i:s'),
                'fecha_publicacion_publica' => now()->subDays(3)->format('Y-m-d H:i:s'),
                'imagen_trabajo' => 'estatico/bolsa/Especialista en Marketing Digital.jpg',
            ],
            (object)[
                'id_aprobado' => 999004,
                'titulo_puesto' => 'Contador General',
                'nombre_empresa' => 'Finanzas & Asesoría Ltda',
                'descripcion_puesto' => 'Llevar contabilidad, impuestos y asesoría fiscal de empresas constructoras.',
                'modalidad' => 'Presencial',
                'categoria' => 'Finanzas y Contabilidad',
                'salario' => 2500.00,
                'ubicacion' => 'Trujillo',
                'fecha_limite_postulacion' => now()->addDays(25)->format('Y-m-d H:i:s'),
                'fecha_publicacion_publica' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'imagen_trabajo' => 'estatico/bolsa/Contador General.jpg',
            ],
            (object)[
                'id_aprobado' => 999005,
                'titulo_puesto' => 'Ingeniero de Sistemas',
                'nombre_empresa' => 'DataSys Solutions',
                'descripcion_puesto' => 'Desarrollo de sistemas empresariales, infraestructura y soporte técnico.',
                'modalidad' => 'Remoto',
                'categoria' => 'Tecnología y Desarrollo',
                'salario' => 4200.00,
                'ubicacion' => 'Lima',
                'fecha_limite_postulacion' => now()->addDays(22)->format('Y-m-d H:i:s'),
                'fecha_publicacion_publica' => now()->subDays(4)->format('Y-m-d H:i:s'),
                'imagen_trabajo' => 'estatico/bolsa/Ingeniero de Sistemas.jpg',
            ],
            (object)[
                'id_aprobado' => 999006,
                'titulo_puesto' => 'Gerente de Logística',
                'nombre_empresa' => 'Transportes Global SA',
                'descripcion_puesto' => 'Supervisión de distribución, inventario y coordinación de rutas de transporte.',
                'modalidad' => 'Presencial',
                'categoria' => 'Logística, Transporte y Almacén',
                'salario' => 3000.00,
                'ubicacion' => 'Callao',
                'fecha_limite_postulacion' => now()->addDays(12)->format('Y-m-d H:i:s'),
                'fecha_publicacion_publica' => now()->subDays(6)->format('Y-m-d H:i:s'),
                'imagen_trabajo' => 'estatico/bolsa/Gerente de Logística.jpg',
            ],
            (object)[
                'id_aprobado' => 999007,
                'titulo_puesto' => 'Técnico de Soporte IT',
                'nombre_empresa' => 'TechSupport Express',
                'descripcion_puesto' => 'Atención a usuarios, instalación y mantenimiento de equipos informáticos.',
                'modalidad' => 'Híbrido',
                'categoria' => 'Tecnología y Desarrollo',
                'salario' => 1800.00,
                'ubicacion' => 'Cusco',
                'fecha_limite_postulacion' => now()->addDays(19)->format('Y-m-d H:i:s'),
                'fecha_publicacion_publica' => now()->subDays(2)->format('Y-m-d H:i:s'),
                'imagen_trabajo' => 'estatico/bolsa/Técnico de Soporte IT.jpg',
            ],
            (object)[
                'id_aprobado' => 999008,
                'titulo_puesto' => 'Analista de Datos',
                'nombre_empresa' => 'Business Analytics Corp',
                'descripcion_puesto' => 'Análisis de datos, reportes y visualización para decisiones estratégicas.',
                'modalidad' => 'Remoto',
                'categoria' => 'Tecnología y Desarrollo',
                'salario' => 3800.00,
                'ubicacion' => 'Lima',
                'fecha_limite_postulacion' => now()->addDays(21)->format('Y-m-d H:i:s'),
                'fecha_publicacion_publica' => now()->subDays(7)->format('Y-m-d H:i:s'),
                'imagen_trabajo' => 'estatico/bolsa/Analista de Datos.jpg',
            ],
            (object)[
                'id_aprobado' => 999009,
                'titulo_puesto' => 'Supervisor de Almacén',
                'nombre_empresa' => 'Logística Integral SRL',
                'descripcion_puesto' => 'Control de inventario, recepción de mercancía y coordinación de equipo.',
                'modalidad' => 'Presencial',
                'categoria' => 'Logística, Transporte y Almacén',
                'salario' => 2200.00,
                'ubicacion' => 'Piura',
                'fecha_limite_postulacion' => now()->addDays(16)->format('Y-m-d H:i:s'),
                'fecha_publicacion_publica' => now()->subDays(3)->format('Y-m-d H:i:s'),
                'imagen_trabajo' => 'estatico/bolsa/Supervisor de Almacén.jpg',
            ],
            (object)[
                'id_aprobado' => 999010,
                'titulo_puesto' => 'Especialista en Ciberseguridad',
                'nombre_empresa' => 'SecureNet Technologies',
                'descripcion_puesto' => 'Implementación de sistemas de seguridad, auditorías y gestión de riesgos.',
                'modalidad' => 'Remoto',
                'categoria' => 'Tecnología y Desarrollo',
                'salario' => 4500.00,
                'ubicacion' => 'Lima',
                'fecha_limite_postulacion' => now()->addDays(28)->format('Y-m-d H:i:s'),
                'fecha_publicacion_publica' => now()->subDays(8)->format('Y-m-d H:i:s'),
                'imagen_trabajo' => 'estatico/bolsa/Especialista en Ciberseguridad.jpg',
            ],
        ]);
        
        // Aplicar filtros a las ofertas de demostración
        if (!empty($filters['buscar'])) {
            $q = strtolower($filters['buscar']);
            $ofertasDemo = $ofertasDemo->filter(function ($oferta) use ($q) {
                return stripos($oferta->titulo_puesto, $q) !== false ||
                       stripos($oferta->descripcion_puesto, $q) !== false ||
                       stripos($oferta->nombre_empresa, $q) !== false;
            });
        }
        
        if (!empty($filters['modalidad'])) {
            $ofertasDemo = $ofertasDemo->filter(function ($oferta) use ($filters) {
                return $oferta->modalidad === $filters['modalidad'];
            });
        }
        
        if (!empty($filters['categoria'])) {
            $ofertasDemo = $ofertasDemo->filter(function ($oferta) use ($filters) {
                return $oferta->categoria === $filters['categoria'];
            });
        }
        
        if (!empty($filters['salario_min'])) {
            $salarioMin = (float)$filters['salario_min'];
            $ofertasDemo = $ofertasDemo->filter(function ($oferta) use ($salarioMin) {
                return $oferta->salario >= $salarioMin;
            });
        }
        
        // Combinar ofertas de BD con ofertas de demostración filtradas
        $ofertas = $ofertas->concat($ofertasDemo);
        
        $categorias = $this->categoriasDisponibles();

        return view('publicidad.bolsa-trabajo', compact('ofertas', 'filters', 'categorias'));
    }

    public function detalle(int $id)
    {
        $oferta = $this->bolsaTrabajoService->getPublicJobById($id);

        return view('bolsa-trabajo.detalle', compact('oferta'));
    }

    public function postular(int $id)
    {
        $oferta = $this->bolsaTrabajoService->getPublicJobById($id);

        return view('bolsa-trabajo.postular', compact('oferta'));
    }

    public function guardarPostulacion(StorePostulacionRequest $request, int $id)
    {
        $data = $request->validated();
        $data['password'] = Str::random(10);

        $this->bolsaTrabajoService->submitPostulacion($id, $data, $request->file('curriculum_pdf'));

        return redirect('/')->with('success', 'Postulación enviada correctamente');
    }

    public function detalleServicio(int $id)
    {
        $servicio = \Illuminate\Support\Facades\DB::table('servicios_publicos')
            ->select('servicios_publicos.*', 'id_publico_servicio as id')
            ->where('id_publico_servicio', $id)
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '>=', now()->format('Y-m-d'))
            ->firstOrFail();

        return view('publicidad.detalle-servicio', compact('servicio'));
    }
}
