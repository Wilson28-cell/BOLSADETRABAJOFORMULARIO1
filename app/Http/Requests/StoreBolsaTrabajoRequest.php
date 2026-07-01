<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'categoria' => 'required',
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
