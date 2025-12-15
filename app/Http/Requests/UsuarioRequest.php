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
        // 1. Obtener el método de la petición (POST para crear, PUT/PATCH para editar)
        $method = $this->method(); 

        // 2. Obtener el ID del usuario actual si estamos editando. 
        // Esto es necesario para que la validación 'unique' del email funcione correctamente.
        // Asumo que tu ruta resource usa {usuario}.
        $usuarioId = $this->route('usuario') ? $this->route('usuario')->id : null; 

        return [
            'nombre' => ['required', 'string', 'max:50'],
            'apellido' => ['nullable', 'string', 'max:50'],
            'celular' => ['nullable', 'string', 'max:20', 'min:8'],
            
            // Validación de Email: Debe ser único, EXCLUYENDO el usuario actual si estamos editando.
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:100', 
                Rule::unique('usuarios', 'email')->ignore($usuarioId)
            ],
            
            // CORRECCIÓN CLAVE PARA LA CONTRASEÑA
            'password' => $method == 'POST' 
                ? 'required|string|min:8'   // REQUERIDO al CREAR (POST)
                : 'nullable|string|min:8',  // OPCIONAL al EDITAR (PUT/PATCH)

            'foto' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', 'integer'], // Cambiado a integer ya que es 0 o 1
            'rol_id' => ['required', 'integer', 'exists:roles,id'],
        ];
    }
}