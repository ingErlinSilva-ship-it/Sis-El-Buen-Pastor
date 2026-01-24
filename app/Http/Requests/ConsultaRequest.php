<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultaRequest extends FormRequest
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
			'paciente_id' => 'required',
			'medico_id' => 'required',
			'cita_id' => 'required',
			'sintomas' => 'required|string',
			'diagnostico' => 'required|string',
			'prescripcion' => 'required|string',
			'presion_arterial' => 'nullable|string',
			'observaciones' => 'nullable|string',
        ];
    }
}
