<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PacienteRequest extends FormRequest
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
        'fecha_nacimiento' => 'required|date',
        'cedula' => [
            'required',
            'string',
            'regex:/^[0-9]{3}-[0-9]{6}-[0-9]{4}[A-Z]$/i',
            'unique:pacientes,cedula,' . ($this->paciente?->id ?? ''), // Esto permite editar sin error de "ya existe"
        ],
			'direccion' => 'nullable|string|min:5',
			'tipo_sangre' => 'string|required|in:O+,O-,A+,A-,B+,B-,AB+,AB-',
			'usuario_id' => 'required',
        ];
    }



    public function messages(): array
    {
        return [
        'cedula.required' => 'El campo cédula es obligatorio.',
        'cedula.regex' => 'El formato de la cédula debe ser 000-000000-0000X.',
        'cedula.unique' => 'Esta cédula ya se encuentra registrada.',
        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligato',
        'fecha_nacimiento.date' => 'Ingrese una fecha válida.',
        ];
    }
}
