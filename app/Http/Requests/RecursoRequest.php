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
            'id_categoria' => 'required|exists:categorias,id',
            'nro_serie' => ['required', 'string', 'unique:recursos,nro_serie,' . $this->route('recurso')],
            'id_marca' => 'required|exists:marcas,id',
            'fuente_adquisicion' => 'required|string|max:255',
            'estado_conservacion' => 'required|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'observacion' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'nro_serie.unique' => 'El número de serie ya existe. Por favor, use otro.',
        ];
    }
}
