<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => [
            'required', 
            'string', 
            'max:50', 
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u' // Solo letras, tildes y espacios
            ],

            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'unique:'.User::class, 
                'regex:/^.+@(gmail|outlook|hotmail|yahoo)\.(com|es|net)$/i'
            ],

            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'apellido' => [
            'nullable', 
            'string', 
            'max:50', 
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u' // Solo letras, tildes y espacios
            ],
            'celular' => ['nullable', 'numeric', 'digits:8'], // Nuevo
            ], [
                'email.regex' => 'El correo electrónico debe pertenecer a un dominio válido (Gmail, Outlook, Hotmail o Yahoo).',
                'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
                'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'celular' => $request->celular,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
            'rol_id' => 3, // Asignar el ID de Rol para Paciente
            'estado' => true
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
