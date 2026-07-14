<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBolsaTrabajoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_empresa' => 'required',
            'ruc' => 'required|unique:registro_bolsadetrabajo_empresa,ruc',
            'correo_electronico' => 'required|email',
            'telefono' => 'required',
            'responsable_representante' => 'required',
            'direccion' => 'required',
            'documento_validacion' => 'required|mimes:pdf|max:10240',
            'titulo_puesto' => 'required',
            'categoria' => [
                'required',
                Rule::in([
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
                ]),
            ],
            'modalidad' => 'required',
            'ubicacion' => 'required',
            'salario' => 'required|numeric',
            'fecha_inicio_convocatoria' => 'required|date',
            'fecha_limite_postulacion' => 'required|date|after_or_equal:fecha_inicio_convocatoria',
            'descripcion_puesto' => 'required',
            'requisitos' => 'required',
            'imagen_trabajo' => 'required|image|max:5120',
        ];
    }
}
