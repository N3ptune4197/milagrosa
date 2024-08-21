<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecursoRequest extends FormRequest
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
			'nombre' => 'required|string|max:255',
        'id_categoria' => 'required|exists:categorias,id', // Cambiado a id_categoria
        'modelo' => 'nullable|string|max:255',
        'nro_serie' => 'nullable|string|max:255',
        'id_marca' => 'required|exists:marcas,id',
        ];
    }
}
