<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class ValidationFormsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'nombre_empresa' => 'InnovaTech S.A.C.',
                'ruc' => '20345678901',
                'correo_electronico' => 'rrhh@innovatech.com',
                'telefono' => '987654321',
                'responsable_representante' => 'Luz Vega',
                'direccion' => 'Av. Javier Prado 1234, Lima',
                'titulo_puesto' => 'Analista de Datos Junior',
                'descripcion_puesto' => 'Atención y análisis de información comercial para apoyar la toma de decisiones.',
                'requisitos' => "Conocimientos en Excel y SQL\nDeseable experiencia en BI\nCapacidad analítica y comunicación clara",
                'modalidad' => 'Remoto',
                'categoria' => 'Tecnología',
                'salario' => 2500.00,
                'ubicacion' => 'Lima',
                'fecha_inicio_convocatoria' => Carbon::now()->format('Y-m-d'),
                'fecha_limite_postulacion' => Carbon::now()->addDays(28)->format('Y-m-d'),
            ],
            [
                'nombre_empresa' => 'Consorcio Salud Viva S.A.C.',
                'ruc' => '20456789012',
                'correo_electronico' => 'rrhh@saludviva.com',
                'telefono' => '945123876',
                'responsable_representante' => 'Dr. Luis Fernández',
                'direccion' => 'Av. Arequipa 420, Miraflores, Lima',
                'titulo_puesto' => 'Coordinador de Enfermería',
                'descripcion_puesto' => 'Gestión de turnos y coordinación de personal de enfermería en centros de atención.',
                'requisitos' => "Título profesional en Enfermería\nExperiencia mínima 2 años\nHabilidades de liderazgo",
                'modalidad' => 'Presencial',
                'categoria' => 'Salud',
                'salario' => 3200.00,
                'ubicacion' => 'Lima',
                'fecha_inicio_convocatoria' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'fecha_limite_postulacion' => Carbon::now()->addDays(25)->format('Y-m-d'),
            ],
            [
                'nombre_empresa' => 'Grupo AgroFresco',
                'ruc' => '20567890123',
                'correo_electronico' => 'contacto@agrofresco.com',
                'telefono' => '961234578',
                'responsable_representante' => 'Ana María Quispe',
                'direccion' => 'Av. La Marina 5678, Callao',
                'titulo_puesto' => 'Ingeniero Agrónomo',
                'descripcion_puesto' => 'Implementar prácticas agrícolas sostenibles y supervisar cultivos.',
                'requisitos' => "Título en Agronomía\nExperiencia en campo\nConocimiento de sistemas de riego",
                'modalidad' => 'Semi-presencial',
                'categoria' => 'Agricultura',
                'salario' => 2800.00,
                'ubicacion' => 'Callao',
                'fecha_inicio_convocatoria' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'fecha_limite_postulacion' => Carbon::now()->addDays(18)->format('Y-m-d'),
            ],
            [
                'nombre_empresa' => 'Servicios Comerciales Andes',
                'ruc' => '20678901234',
                'correo_electronico' => 'rrhh@andescomercio.com',
                'telefono' => '923456789',
                'responsable_representante' => 'Carlos Torres',
                'direccion' => 'Calle Los Alisos 127, San Isidro, Lima',
                'titulo_puesto' => 'Ejecutivo de Ventas',
                'descripcion_puesto' => 'Atender clientes y promover soluciones comerciales para la cartera asignada.',
                'requisitos' => "Experiencia en ventas de 1 año\nOrientación al cliente\nDisponibilidad para movilidad ligera",
                'modalidad' => 'Mixto',
                'categoria' => 'Comercio',
                'salario' => 2200.00,
                'ubicacion' => 'Lima',
                'fecha_inicio_convocatoria' => Carbon::now()->format('Y-m-d'),
                'fecha_limite_postulacion' => Carbon::now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'nombre_empresa' => 'Constructora Horizonte',
                'ruc' => '20789012345',
                'correo_electronico' => 'rrhh@horizonte.com.pe',
                'telefono' => '944556677',
                'responsable_representante' => 'María Castillo',
                'direccion' => 'Av. El Polo 890, Surco, Lima',
                'titulo_puesto' => 'Supervisor de Obra',
                'descripcion_puesto' => 'Supervisar actividades en obras civiles y verificar cumplimiento de plazos y seguridad.',
                'requisitos' => "Experiencia en supervisión de obra\nConocimiento de normas de seguridad\nDisponibilidad full-time",
                'modalidad' => 'Presencial',
                'categoria' => 'Construcción',
                'salario' => 4200.00,
                'ubicacion' => 'Lima',
                'fecha_inicio_convocatoria' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'fecha_limite_postulacion' => Carbon::now()->addDays(20)->format('Y-m-d'),
            ],
            [
                'nombre_empresa' => 'Logística Andina',
                'ruc' => '20890123456',
                'correo_electronico' => 'talentohumano@logisticaandina.com',
                'telefono' => '988776655',
                'responsable_representante' => 'Diego Rojas',
                'direccion' => 'Parque Industrial 45, Ate, Lima',
                'titulo_puesto' => 'Coordinador de Transporte',
                'descripcion_puesto' => 'Planificar rutas, coordinar flota y garantizar entregas oportunas.',
                'requisitos' => "Experiencia en logística\nBuen manejo de indicadores\nDisponibilidad para operar en horarios rotativos",
                'modalidad' => 'Mixto',
                'categoria' => 'Logística',
                'salario' => 2600.00,
                'ubicacion' => 'Lima',
                'fecha_inicio_convocatoria' => Carbon::now()->format('Y-m-d'),
                'fecha_limite_postulacion' => Carbon::now()->addDays(32)->format('Y-m-d'),
            ],
            [
                'nombre_empresa' => 'Marketing Digital Perú',
                'ruc' => '20901234567',
                'correo_electronico' => 'hola@marketingdigitalperu.com',
                'telefono' => '975332211',
                'responsable_representante' => 'Natalia Gómez',
                'direccion' => 'Malecón de la Reserva 230, Miraflores, Lima',
                'titulo_puesto' => 'Community Manager',
                'descripcion_puesto' => 'Gestionar redes sociales, crear contenido y monitorear campañas digitales.',
                'requisitos' => "Experiencia en redes sociales\nManejo de herramientas de publicación\nCreatividad y comunicación",
                'modalidad' => 'Remoto',
                'categoria' => 'Marketing',
                'salario' => 1800.00,
                'ubicacion' => 'Lima',
                'fecha_inicio_convocatoria' => Carbon::now()->format('Y-m-d'),
                'fecha_limite_postulacion' => Carbon::now()->addDays(14)->format('Y-m-d'),
            ],
            [
                'nombre_empresa' => 'Finanzas Globales S.A.C.',
                'ruc' => '20123456789',
                'correo_electronico' => 'capitalhumano@finanzasglobales.com',
                'telefono' => '989998887',
                'responsable_representante' => 'Jorge Peña',
                'direccion' => 'Jirón de la Unión 567, Cercado de Lima',
                'titulo_puesto' => 'Asistente Contable',
                'descripcion_puesto' => 'Apoyar en registros contables y conciliaciones bancarias.',
                'requisitos' => "Formación contable o afín\nManejo de Excel\nResponsabilidad y orden",
                'modalidad' => 'Presencial',
                'categoria' => 'Finanzas',
                'salario' => 2100.00,
                'ubicacion' => 'Lima',
                'fecha_inicio_convocatoria' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'fecha_limite_postulacion' => Carbon::now()->addDays(35)->format('Y-m-d'),
            ],
            [
                'nombre_empresa' => 'Academia Nexus',
                'ruc' => '20234567890',
                'correo_electronico' => 'registro@academianexus.edu.pe',
                'telefono' => '912345678',
                'responsable_representante' => 'Silvia Retamozo',
                'direccion' => 'Av. Universitaria 1234, San Miguel, Lima',
                'titulo_puesto' => 'Docente de Matemáticas',
                'descripcion_puesto' => 'Impartir clases de matemáticas a nivel preuniversitario y acompañar a los estudiantes.',
                'requisitos' => "Título universitario en educación\nExperiencia docente\nCapacidad de comunicación",
                'modalidad' => 'Presencial',
                'categoria' => 'Educación',
                'salario' => 2400.00,
                'ubicacion' => 'Lima',
                'fecha_inicio_convocatoria' => Carbon::now()->format('Y-m-d'),
                'fecha_limite_postulacion' => Carbon::now()->addDays(22)->format('Y-m-d'),
            ],
            [
                'nombre_empresa' => 'Turismo Sol y Mar',
                'ruc' => '20345678099',
                'correo_electronico' => 'contacto@turismosolymar.com',
                'telefono' => '965432100',
                'responsable_representante' => 'Emma Castillo',
                'direccion' => 'Malecón Cisneros 765, Chorrillos, Lima',
                'titulo_puesto' => 'Promotor de Ventas Turísticas',
                'descripcion_puesto' => 'Atender clientes interesados en paquetes turísticos y asesorar sobre destinos.',
                'requisitos' => "Experiencia en atención al cliente\nCapacidad de venta\nInterés en turismo",
                'modalidad' => 'Mixto',
                'categoria' => 'Turismo',
                'salario' => 2000.00,
                'ubicacion' => 'Lima',
                'fecha_inicio_convocatoria' => Carbon::now()->format('Y-m-d'),
                'fecha_limite_postulacion' => Carbon::now()->addDays(16)->format('Y-m-d'),
            ],
        ];

        foreach ($items as $item) {
            $existingCompany = DB::table('registro_bolsadetrabajo_empresa')
                ->where('ruc', $item['ruc'])
                ->first();

            if ($existingCompany) {
                continue;
            }

            $registroData = [
                'nombre_empresa' => $item['nombre_empresa'],
                'ruc' => $item['ruc'],
                'correo_electronico' => $item['correo_electronico'],
                'telefono' => $item['telefono'],
                'responsable_representante' => $item['responsable_representante'],
                'direccion' => $item['direccion'],
                'documento_validacion' => '',
                'estado' => 'PENDIENTE',
            ];

            if (Schema::hasColumn('registro_bolsadetrabajo_empresa', 'veces_restaurado')) {
                $registroData['veces_restaurado'] = 0;
            }

            if (Schema::hasColumn('registro_bolsadetrabajo_empresa', 'created_at')) {
                $registroData['created_at'] = Carbon::now();
            }
            if (Schema::hasColumn('registro_bolsadetrabajo_empresa', 'updated_at')) {
                $registroData['updated_at'] = Carbon::now();
            }

            $companyId = DB::table('registro_bolsadetrabajo_empresa')->insertGetId($registroData);

            $publicationData = [
                'id_empresa' => $companyId,
                'titulo_puesto' => $item['titulo_puesto'],
                'descripcion_puesto' => $item['descripcion_puesto'],
                'requisitos' => $item['requisitos'],
                'imagen_trabajo' => '',
                'modalidad' => $item['modalidad'],
                'categoria' => $item['categoria'],
                'salario' => $item['salario'],
                'ubicacion' => $item['ubicacion'],
                'fecha_inicio_convocatoria' => $item['fecha_inicio_convocatoria'],
                'fecha_limite_postulacion' => $item['fecha_limite_postulacion'],
            ];

            if (Schema::hasColumn('publicaciones_trabajo', 'estado')) {
                $publicationData['estado'] = 'PENDIENTE';
            }

            if (Schema::hasColumn('publicaciones_trabajo', 'created_at')) {
                $publicationData['created_at'] = Carbon::now();
            }
            if (Schema::hasColumn('publicaciones_trabajo', 'updated_at')) {
                $publicationData['updated_at'] = Carbon::now();
            }

            DB::table('publicaciones_trabajo')->insert($publicationData);
        }
    }
}
