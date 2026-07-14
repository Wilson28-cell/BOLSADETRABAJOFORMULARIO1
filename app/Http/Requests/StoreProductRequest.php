<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            'responsable_representante' => 'required',
            'direccion' => 'required',
            'documento_validacion' => 'required|mimes:pdf|max:10240',
            'nombre_producto' => 'required',
            'descripcion' => 'required',
            'categoria' => [
                'required',
                Rule::in([
                    'Alimentos y Bebidas',
                    'Moda y Calzado',
                    'Tecnología y Electrónica',
                    'Cuidado Personal y Belleza',
                    'Hogar y Decoración',
                    'Salud y Bienestar',
                    'Juguetes y Niños',
                    'Deportes y Aire Libre',
                    'Limpieza y Mascotas',
                    'Oficina y Papelería',
                ]),
            ],
            'ubicacion_ciudad' => 'required',
            'telefono_contacto' => 'required',
            'correo_contacto' => 'required|email',
            'direccion_atencion' => 'required',
            'imagen_producto' => 'required|image|max:5120',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ];
    }
}
