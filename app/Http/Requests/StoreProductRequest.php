<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'categoria' => 'required',
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
