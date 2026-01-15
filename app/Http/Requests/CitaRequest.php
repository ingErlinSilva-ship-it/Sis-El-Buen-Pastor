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
        return [
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id'   => 'required|exists:medicos,id',
            'fecha'       => 'required|date',
            'hora'        => 'required',
            'duracion_minutos' => 'required|integer',
            'motivo'      => 'nullable|string', 
            'estado'      => 'required|in:pendiente,confirmada,cancelada',
            'origen'      => 'required|string',
            'chat_session_id'    => 'nullable|string', 
            'token_confirmacion' => 'nullable|string', 
        ];
    }
}
