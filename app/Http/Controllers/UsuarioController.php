<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;// ¡IMPORTANTE para la contraseña!
use Illuminate\Support\Facades\Auth;// Necesario para Auth::user()


class UsuarioController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

    // LÓGICA PARA EL POP-UP: Si se solicita ver usuarios sin expediente
    if ($request->has('sin_expediente') && $user->rol_id == 1) {
        $usuarios = Usuario::with('role')
            ->where('rol_id', 3)
            ->whereDoesntHave('paciente') // Filtra usuarios que NO existen en la tabla pacientes
            ->paginate();
    } 
    // FILTRO: Si es Paciente (rol 3) O Doctor (rol 2), solo se ven a sí mismos
    elseif ($user->rol_id == 3 || $user->rol_id == 2) {
        $usuarios = Usuario::with('role')->where('id', $user->id)->paginate();
    } 
    else {
        // Solo el Administrador (rol 1) ve a todos los usuarios
        $usuarios = Usuario::with('role')->paginate();
    }

    return view('usuario.index', compact('usuarios'))
        ->with('i', ($request->input('page', 1) - 1) * $usuarios->perPage());
    }
    public function create(): View
    {
        $usuario = new Usuario();
        $roles = Role::all();
        return view('usuario.create', compact('usuario', 'roles'));
    }
    public function store(UsuarioRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('usuarios', 'public');
        }
        
        $data['password'] = Hash::make($request->input('password'));
        
        Usuario::create($data); 

        return Redirect::route('usuario.index')
            ->with('success', '¡Listo! La nueva cuenta del usuario ha sido creada con éxito.');
    }
    public function show($id): View
    {
        $usuario = Usuario::findOrFail($id);
        
        // SEGURIDAD: Pacientes y Doctores solo ven su propio "Show"
        if (Auth::user()->rol_id != 1 && $usuario->id !== Auth::id()) {
            abort(403);
        }

        return view('usuario.show', compact('usuario'));
    }
    public function edit($id): View
    {
        $usuario = Usuario::findOrFail($id);
        
        // SEGURIDAD: Si no es Admin, solo puede editarse a sí mismo
        if (Auth::user()->rol_id != 1 && $usuario->id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar otros usuarios.');
        }

        $roles = Role::all();
        return view('usuario.edit', compact('usuario', 'roles'));
    }
    public function update(UsuarioRequest $request, Usuario $usuario): RedirectResponse
    {
        $currentUser = Auth::user();

        // SEGURIDAD: Si no es Admin, solo puede actualizar su propio ID
        if ($currentUser->rol_id != 1 && $usuario->id !== $currentUser->id) {
            abort(403);
        }

        $data = $request->validated();

        // 1. Manejo de Quitar Foto
        if ($request->input('remove_photo') == '1') {
            if ($usuario->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($usuario->foto);
                $usuario->foto = null;
                $usuario->save();
            }
        }

        // 2. Nueva Foto
        if ($request->hasFile('foto')) {
            // Eliminar foto vieja si existe (opcional, pero recomendado)
            if ($usuario->foto && file_exists(public_path('storage/fotos/' . $usuario->foto))) {
                unlink(public_path('storage/fotos/' . $usuario->foto));
            }

            $file = $request->file('foto');
            $nombreFoto = time() . '_' . $file->getClientOriginalName();
            
            // LA CLAVE: Mover directamente a public/storage/fotos
            $file->move(public_path('storage/fotos'), $nombreFoto);
            
            // Guardamos solo el nombre del archivo en el array de datos
            $data['foto'] = $nombreFoto;
        }
        
        // 3. Manejo de Contraseña
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // --- PROTECCIÓN EXTRA ---
        // Si el usuario NO ES ADMINISTRADOR (Paciente o Doctor), bloqueamos Rol y Estado
        if ($currentUser->rol_id != 1) {
            unset($data['rol_id']);
            unset($data['estado']);
        }
        
        $usuario->update($data);

        // Lógica de Logout forzado (si el admin lo desactiva o cambia rol)
        if ($usuario->estado == 0) { 
            \Illuminate\Support\Facades\Cache::put('force_logout_user_' . $usuario->id, 'desactivado', now()->addDay());
        } 
        elseif ($usuario->wasChanged('rol_id')) {
            \Illuminate\Support\Facades\Cache::put('force_logout_user_' . $usuario->id, 'rol_cambiado', now()->addDay());
        }

        return Redirect::route('usuario.index')->with('success', 'Datos actualizados con éxito.');
    }

    public function destroy($id): RedirectResponse
    {
        if (Auth::user()->rol_id !== 1) {
            abort(403);
        }

        Usuario::findOrFail($id)->delete();

        return Redirect::route('usuario.index')
            ->with('success', '¡Listo! La cuenta del usuario se ha eliminado con éxito.');
    }
}