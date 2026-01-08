<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PacienteRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $pacientes = Paciente::paginate();

        return view('paciente.index', compact('pacientes'))
            ->with('i', ($request->input('page', 1) - 1) * $pacientes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ID_ROL_PACIENTE = 3;// aca esta el Id del rol de paciente para que solo los que tienen ese rol se puedan seleccionar
        $paciente = new Paciente();

        $usuarios = Usuario::where('rol_id', $ID_ROL_PACIENTE)
        ->whereDoesntHave('paciente')
        ->pluck('nombre', 'id');

        return view('paciente.create', compact('paciente', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PacienteRequest $request): RedirectResponse
    {
        Paciente::create($request->validated());

        return Redirect::route('paciente.index')
            ->with('success', '¡Listo! La nueva cuenta de Paciente ha sido creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $paciente = Paciente::find($id);

        return view('paciente.show', compact('paciente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $paciente = Paciente::find($id);

        //Traemos los usuarios (Pluck crea un array de [id => nombre])
        $usuarios = \App\Models\Usuario::pluck('nombre', 'id'); 
        return view('paciente.edit', compact('paciente', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PacienteRequest $request, Paciente $paciente): RedirectResponse
    {
        $paciente->update($request->validated());

        return Redirect::route('paciente.index')
            ->with('success', '¡Listo! Los datos del Paciente se han Actualizado con éxito.');
    }

    public function destroy($id): RedirectResponse
    {
        Paciente::find($id)->delete();

        return Redirect::route('paciente.index')
            ->with('success', '¡Listo! La Cuenta del Paciente se ha Eliminado con éxito.');
    }
}
