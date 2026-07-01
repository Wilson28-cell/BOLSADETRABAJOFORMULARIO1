<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostulacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombres' => 'required',
            'apellidos' => 'required',
            'dni' => 'required',
            'correo_electronico' => 'required|email',
            'telefono' => 'required',
            'direccion' => 'required',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required',
            'curriculum_pdf' => 'required|mimes:pdf|max:5000',
        ];
    }
}
