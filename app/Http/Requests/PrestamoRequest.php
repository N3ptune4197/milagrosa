<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrestamoRequest extends FormRequest
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
			'idPersonal' => 'required',
			'fecha_prestamo' => 'required', 'date', 'after_or_equal:today',
			'fecha_devolucion' => 'required',
			'cantidad_total' => 'required',
			'observacion' => 'nullable|string',
        ];
    }
}
