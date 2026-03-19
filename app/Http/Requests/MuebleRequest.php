<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MuebleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mueble' => 'required|string|max:150',
            'terminado_id' => 'required|integer|exists:terminado,terminado_id',
            'material_id' => 'required|integer|exists:materiales,materiales_id',
            'departamento_id' => 'required|integer|exists:departamentos,departamento_id',
            'stock' => 'nullable|integer|min:0',
            'encerado' => 'nullable|integer|min:0',
            'sincera' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string|max:1000',
            'costo' => 'nullable|integer|min:0',
            'barniz' => 'nullable|integer|min:0',
            'imageUrl' => 'nullable|string|max:1000',
        ];
    }
    public function messages(): array
    {
        return [
            'mueble.required'       => 'El nombre del mueble es obligatorio.',
            'mueble.string'         => 'El nombre del mueble debe ser texto.',
            'mueble.max'            => 'El nombre del mueble no puede superar 150 caracteres.',

            'terminado_id.required' => 'Debes seleccionar un terminado.',
            'terminado_id.exists'   => 'El terminado seleccionado no existe.',

            'material_id.required'  => 'Debes seleccionar un material.',
            'material_id.exists'    => 'El material seleccionado no existe.',

            'departamento_id.required' => 'Debes seleccionar un departamento.',
            'departamento_id.exists'   => 'El departamento seleccionado no existe.',

            'stock.integer'         => 'El stock debe ser un número entero.',
            'stock.min'             => 'El stock no puede ser negativo.',

            'observaciones.string'  => 'Las observaciones deben ser texto.',
            'observaciones.max'     => 'Las observaciones no pueden superar 1000 caracteres.',

            'costo.integer'         => 'El costo debe ser un número entero.',
            'costo.min'             => 'El costo no puede ser negativo.',

            'imageUrl.string'       => 'La URL de la imagen debe ser texto.',
            'imageUrl.max'          => 'La URL de la imagen no puede superar 1000 caracteres.',
        ];
    }
}
