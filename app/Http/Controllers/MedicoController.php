<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Usuario;
use App\Models\Especialidade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MedicoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Importante para los filtros

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // FILTRO: Si es Doctor, solo se ve a sí mismo. Si es Admin, ve a todos.
        if ($user->rol_id == 2) {
            $medicos = Medico::where('usuario_id', $user->id)->paginate();
        } else {
            $medicos = Medico::paginate();
        }

        return view('medico.index', compact('medicos'))
            ->with('i', ($request->input('page', 1) - 1) * $medicos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ID_ROL_DOCTOR = 2;
        $medico = new Medico();
        
        $usuarios = Usuario::where('rol_id', $ID_ROL_DOCTOR)
            ->whereDoesntHave('medico')
            ->pluck('nombre', 'id');
        
        $especialidades = Especialidade::pluck('nombre', 'id');
        return view('medico.create', compact('medico', 'usuarios', 'especialidades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicoRequest $request): RedirectResponse
    {
        Medico::create($request->validated());

        return Redirect::route('medico.index')
            ->with('success', '¡Listo! La nueva cuenta del Médico ha sido creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $medico = Medico::findOrFail($id);
        $user = Auth::user();

        // Seguridad: Un doctor no puede ver perfiles de otros médicos vía URL
        if ($user->rol_id == 2 && $medico->usuario_id !== $user->id) {
            abort(403);
        }

        return view('medico.show', compact('medico'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $medico = Medico::findOrFail($id);
        $user = Auth::user();

        // Seguridad: Un doctor solo puede editar su propio perfil profesional
        if ($user->rol_id == 2 && $medico->usuario_id !== $user->id) {
            abort(403, 'No tienes permiso para editar este perfil médico.');
        }

        $ID_ROL_DOCTOR = 2;
        $usuarios = Usuario::where('rol_id', $ID_ROL_DOCTOR)
            ->where(function($query) use ($medico) {
                $query->whereDoesntHave('medico')
                    ->orWhere('id', $medico->usuario_id);
            })
            ->pluck('nombre', 'id');
            
        $especialidades = Especialidade::pluck('nombre', 'id');

        return view('medico.edit', compact('medico', 'usuarios', 'especialidades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MedicoRequest $request, Medico $medico): RedirectResponse
    {
        $user = Auth::user();

        // Doble verificación en el update
        if ($user->rol_id == 2 && $medico->usuario_id !== $user->id) {
            abort(403);
        }

        $data = $request->validated();

        // Protección de campos para el Médico:
        // Si quieres que el médico NO pueda cambiar su especialidad ni su usuario vinculado:
        if ($user->rol_id == 2) {
            unset($data['usuario_id']);
            unset($data['especialidad_id']); // Asumiendo que así se llama tu FK
        }

        $medico->update($data);

        return Redirect::route('medico.index')
            ->with('success', '¡Listo! Los datos se han actualizado con éxito.');
    }

    public function destroy($id): RedirectResponse
    {
        // Solo el Admin puede eliminar médicos
        if (Auth::user()->rol_id !== 1) {
            abort(403);
        }

        Medico::findOrFail($id)->delete();

        return Redirect::route('medico.index')
            ->with('success', '¡Listo! La cuenta se ha eliminado con éxito.');
    }
}