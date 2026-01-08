<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Usuario; // modelo de usuario
use App\Models\Especialidade; // modelo de especialidad
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MedicoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $medicos = Medico::paginate();

        return view('medico.index', compact('medicos'))
            ->with('i', ($request->input('page', 1) - 1) * $medicos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ID_ROL_DOCTOR = 2; // aca esta el Id del rol de doctor para que solo los que tienen ese rol se puedan seleccionar
        $medico = new Medico();
        
        // Obtener la lista de Usuarios que son Doctores Y NO son médicos
        $usuarios = Usuario::where('rol_id', $ID_ROL_DOCTOR)
        ->whereDoesntHave('medico')
        ->pluck('nombre', 'id');
        
        // ... (El resto del código para especialidades es el mismo)
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
        $medico = Medico::find($id);

        return view('medico.show', compact('medico'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $medico = Medico::find($id);
        $ID_ROL_DOCTOR = 2;

        $usuarios = Usuario::where('rol_id', $ID_ROL_DOCTOR)
            ->where(function($query) use ($medico) {
                $query->whereDoesntHave('medico')
                    ->orWhere('id', $medico->usuario_id);
            })
            ->pluck('nombre', 'id');
        $especialidades = \App\Models\Especialidade::pluck('nombre', 'id');

        return view('medico.edit', compact('medico', 'usuarios', 'especialidades'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MedicoRequest $request, Medico $medico): RedirectResponse
    {
        $medico->update($request->validated());

        return Redirect::route('medico.index')
            ->with('success', '¡Listo! Los datos del Médico se ha actualizado con éxito.');
    }

    public function destroy($id): RedirectResponse
    {
        Medico::find($id)->delete();

        return Redirect::route('medico.index')
            ->with('success', '¡Listo! La cuenta del Médico se ha eliminado con éxito.');
    }
}