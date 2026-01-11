<?php

namespace App\Http\Controllers;

use App\Models\PacientesAlergium;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PacientesAlergiumRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PacientesAlergiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $pacientesAlergia = PacientesAlergium::paginate();

        return view('pacientes-alergium.index', compact('pacientesAlergia'))
            ->with('i', ($request->input('page', 1) - 1) * $pacientesAlergia->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $pacientesAlergium = new PacientesAlergium();

        return view('pacientes-alergium.create', compact('pacientesAlergium'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PacientesAlergiumRequest $request): RedirectResponse
    {
        PacientesAlergium::create($request->validated());

        return Redirect::route('pacientes-alergia.index')
            ->with('success', 'PacientesAlergium created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $pacientesAlergium = PacientesAlergium::find($id);

        return view('pacientes-alergium.show', compact('pacientesAlergium'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $pacientesAlergium = PacientesAlergium::find($id);

        return view('pacientes-alergium.edit', compact('pacientesAlergium'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PacientesAlergiumRequest $request, PacientesAlergium $pacientesAlergium): RedirectResponse
    {
        $pacientesAlergium->update($request->validated());

        return Redirect::route('pacientes-alergia.index')
            ->with('success', 'PacientesAlergium updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        PacientesAlergium::find($id)->delete();

        return Redirect::route('pacientes-alergia.index')
            ->with('success', 'PacientesAlergium deleted successfully');
    }
}
