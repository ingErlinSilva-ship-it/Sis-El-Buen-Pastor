<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MedicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtenemos el ID del médico para la regla unique al editar
        $medico = $this->route('medico');
        $medicoId = is_object($medico) ? $medico->id : $medico;

        return [
            'usuario_id' => ['required', 'exists:usuarios,id'],
            'especialidad_id' => ['required', 'exists:especialidades,id'],

            // REGLAS:
            // numeric: Solo números
            // digits_between: Mínimo 3, Máximo 6
            // unique: Único en la tabla medicos (excepto el actual)
            'codigo_minsa' => [
                'required',
                'numeric',
                'digits_between:3,6',
                Rule::unique('medicos', 'codigo_minsa')->ignore($medicoId)
            ],

            'descripcion' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo_minsa.required' => 'El código MINSA es obligatorio.',
            'codigo_minsa.numeric' => 'El código MINSA solo puede contener números.',
            'codigo_minsa.digits_between' => 'El código MINSA debe tener entre 3 y 6 dígitos.',
            'codigo_minsa.unique' => 'Este código MINSA ya ha sido registrado por otro médico.',
        ];
    }
}