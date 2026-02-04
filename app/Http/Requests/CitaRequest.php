<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitaRequest extends FormRequest
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
        // Definimos el array base
        $rules = [
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id'   => 'required|exists:medicos,id',
            'hora'        => 'required',
            'duracion_minutos' => 'required|integer',
            'motivo'      => 'nullable|string', 
            'estado'      => 'required|in:pendiente,confirmada,asistida,cancelada',
            'origen'      => 'required|string',
        ];

        // Ahora aplicamos la lógica de la fecha antes de retornar
        if ($this->isMethod('post')) {
            // En CREATE: Obligatorio hoy o futuro
            $rules['fecha'] = 'required|date|after_or_equal:today';
        } else {
            // En EDIT: Solo requerido (permite pasado para historial)
            $rules['fecha'] = 'required|date';
        }

        return $rules; // Retornamos el array completo al final
    }
}
