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
            'nullable',
            'string',
            'regex:/^[0-9]{3}-[0-9]{6}-[0-9]{4}[A-Z]$/i',
            'unique:pacientes,cedula,' . ($this->paciente?->id ?? ''), // Esto permite editar sin error de "ya existe"
        ],
			'direccion' => 'nullable|string|min:5',
			'tipo_sangre' => 'string|nullable|in:O+,O-,A+,A-,B+,B-,AB+,AB-',
			'usuario_id' => 'required',

        // Validaciones condicionales para el Tutor
        'es_menor' => 'nullable|boolean',
        'tutor_nombre' => 'required_if:es_menor,1|nullable|string|max:255',
        'tutor_apellido' => 'required_if:es_menor,1|nullable|string|max:255',
        'tutor_cedula' => [
            'required_if:es_menor,1',
            'nullable',
            'regex:/^[0-9]{3}-[0-9]{6}-[0-9]{4}[A-Z]$/i',
        ],
        'tutor_telefono' => 'required_if:es_menor,1|nullable|numeric|digits:8',
        'tutor_parentesco' => 'required_if:es_menor,1|nullable|in:Padre,Madre,Tutor Legal',
        
        ];

    }



    public function messages(): array
    {
        return [
        'usuario_id.required' => 'Debe seleccionar un usuario para registrar al paciente.',
        'cedula.regex' => 'El formato de la cédula debe ser 000-000000-0000X.',
        'cedula_paciente.unique' => 'Esta cédula ya se encuentra registrada.',
        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligato',
        'fecha_nacimiento.date' => 'Ingrese una fecha válida.',

        // Mensajes para el Tutor
        'tutor_nombre.required_if' => 'El nombre del responsable es obligatorio para menores.',
        'tutor_apellido.required_if' => 'El apellido del responsable es obligatorio para menores.',
        'tutor_cedula.required_if' => 'La cédula del responsable es obligatoria.',
        'tutor_cedula.regex' => 'El formato de la cédula del tutor debe ser 000-000000-0000X.',
        'tutor_telefono.required_if' => 'El teléfono del responsable es obligatorio.',
        'tutor_telefono.digits' => 'El teléfono del tutor debe tener exactamente 8 dígitos.',
        'tutor_parentesco.required_if' => 'Debe indicar el parentesco del responsable.',
        ];
    }
}
