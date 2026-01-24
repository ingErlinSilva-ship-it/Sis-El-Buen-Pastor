<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash; // ¡IMPORTANTE para la contraseña!

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        
        $usuarios = Usuario::with('role')->paginate();

        return view('usuario.index', compact('usuarios'))
            ->with('i', ($request->input('page', 1) - 1) * $usuarios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $usuario = new Usuario();
        $roles = Role::all(); // <-- 1. Cargar roles

        // 2. Pasar $roles a la vista
        return view('usuario.create', compact('usuario', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UsuarioRequest $request): RedirectResponse
{
    // 1. Obtener datos validados (Contraseña en texto plano)
    $data = $request->validated();
    
    // 2. HASHEAR la contraseña y REEMPLAZAR el valor en el array $data
    // Utilizamos $request->input('password') para obtener el valor que viene del formulario.
    $data['password'] = Hash::make($request->input('password'));
    
    // 3. Crear el Usuario con el array que ahora SÍ tiene el hash
    Usuario::create($data); // Aquí se intenta insertar

    return Redirect::route('usuario.index')
        ->with('success', '¡Listo! La nueva cuenta del usuario ha sido creada con éxito.');
}

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $usuario = Usuario::find($id);

        return view('usuario.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $usuario = Usuario::find($id);
        $roles = Role::all(); // <-- 1. Cargar roles para edición
        
        // 2. Pasar $roles a la vista
        return view('usuario.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UsuarioRequest $request, Usuario $usuario): RedirectResponse
{
    $data = $request->validated();
    
    // 1. Manejo de la Contraseña (Opcional al editar)
    if ($request->filled('password')) { // Si el campo 'password' tiene contenido
        $data['password'] = Hash::make($data['password']); // Hashea y lo incluye en $data
    } else {
        unset($data['password']); // Si está vacío, ELIMINA el campo de $data
    }
    
    // 2. Actualizar el Usuario
    $usuario->update($data); // Se actualizan todos los campos, EXCEPTO 'password' si fue eliminado

    // Si el estado es 0 (Inactivo)
    if ($usuario->estado == 0) { 
        \Illuminate\Support\Facades\Cache::put('force_logout_user_' . $usuario->id, 'desactivado', now()->addDay());
    } 
    // Si cambió el rol pero sigue activo
    elseif ($usuario->wasChanged('rol_id')) {
        \Illuminate\Support\Facades\Cache::put('force_logout_user_' . $usuario->id, 'rol_cambiado', now()->addDay());
    }

    return Redirect::route('usuario.index')->with('success', 'Datos actualizados con éxito.');
}

    public function destroy($id): RedirectResponse
    {
        Usuario::find($id)->delete();

        return Redirect::route('usuario.index')
            ->with('success', '¡Listo! La cuenta del usuario se ha eliminado con éxito.');
    }
}