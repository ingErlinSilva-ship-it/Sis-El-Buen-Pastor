<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $method = $this->method(); 

        // Obtenemos el ID de forma segura para la excepción del email único
        $usuarioId = $this->route('usuario') ? $this->route('usuario')->id : null; 

        return [
            'nombre'   => ['required', 'string', 'max:50'],
            'apellido' => ['required', 'string', 'max:50'], // Cambiado a required para ficha médica completa
            'celular'  => ['nullable', 'string', 'max:20', 'min:8'],
            
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:100', 
                Rule::unique('usuarios', 'email')->ignore($usuarioId)
            ],
            
            'password' => $method == 'POST' 
                ? ['required', 'string', 'min:8'] 
                : ['nullable', 'string', 'min:8'],

            // MODIFICACIÓN PARA FOTOS:
            // Validamos que sea una imagen, formatos permitidos y tamaño máximo (2MB)
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            
            'estado' => ['required', 'integer', 'in:0,1'], // Aseguramos que solo sea 0 o 1
            'rol_id' => ['required', 'integer', 'exists:roles,id'],
        ];
    }

    /**
     * Mensajes de error personalizados (Opcional pero recomendado)
     */
    public function messages(): array
    {
        return [
            'foto.image' => 'El archivo seleccionado debe ser una imagen.',
            'foto.mimes' => 'La foto debe estar en formato: jpeg, png o jpg.',
            'foto.max'   => 'La foto no debe pesar más de 2MB.',
            'rol_id.required' => 'Debe asignar un rol de acceso al usuario.',
        ];
    }
}