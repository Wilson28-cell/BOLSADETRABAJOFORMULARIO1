<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_empresa' => 'required',
            'ruc' => 'required|digits:11',
            'correo_electronico' => 'required|email',
            'telefono' => 'required',
            'responsable_representante' => 'nullable',
            'direccion' => 'nullable',
            'documento_validacion' => 'nullable|mimes:pdf|max:10240',
            'nombre_servicio' => 'required',
            'categoria' => [
                'required',
                Rule::in([
                    'Servicios Profesionales',
                    'Servicios de Transporte',
                    'Servicios de Salud y Bienestar',
                    'Servicios Financieros',
                    'Servicios de Telecomunicaciones',
                    'Servicios de Mantenimiento y Reparación',
                    'Servicios de Limpieza y Domésticos',
                    'Servicios de Hostelería y Turismo',
                    'Servicios de Alimentación',
                    'Servicios Públicos',
                ]),
            ],
            'descripcion' => 'required',
            'ubicacion_ciudad' => 'nullable',
            'telefono_contacto' => 'nullable',
            'redes_sociales' => 'nullable',
            'correo_contacto' => 'nullable|email',
            'direccion_atencion' => 'nullable',
            'imagen_servicio' => 'nullable|image|max:5120',
            'horario_atencion' => 'nullable',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ];
    }
}
