<?php

namespace App\Http\Controllers;

use App\Models\PacientesEnfermedad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PacientesEnfermedadRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PacientesEnfermedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $pacientesEnfermedads = PacientesEnfermedad::paginate();

        return view('pacientes-enfermedad.index', compact('pacientesEnfermedads'))
            ->with('i', ($request->input('page', 1) - 1) * $pacientesEnfermedads->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $pacientesEnfermedad = new PacientesEnfermedad();

        return view('pacientes-enfermedad.create', compact('pacientesEnfermedad'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PacientesEnfermedadRequest $request): RedirectResponse
    {
        PacientesEnfermedad::create($request->validated());

        return Redirect::route('pacientes-enfermedads.index')
            ->with('success', 'PacientesEnfermedad created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $pacientesEnfermedad = PacientesEnfermedad::find($id);

        return view('pacientes-enfermedad.show', compact('pacientesEnfermedad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $pacientesEnfermedad = PacientesEnfermedad::find($id);

        return view('pacientes-enfermedad.edit', compact('pacientesEnfermedad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PacientesEnfermedadRequest $request, PacientesEnfermedad $pacientesEnfermedad): RedirectResponse
    {
        $pacientesEnfermedad->update($request->validated());

        return Redirect::route('pacientes-enfermedads.index')
            ->with('success', 'PacientesEnfermedad updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        PacientesEnfermedad::find($id)->delete();

        return Redirect::route('pacientes-enfermedads.index')
            ->with('success', 'PacientesEnfermedad deleted successfully');
    }
}
