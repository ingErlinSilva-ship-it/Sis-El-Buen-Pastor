<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EspecialidadeRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EspecialidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $especialidades = Especialidade::paginate();

        return view('especialidade.index', compact('especialidades'))
            ->with('i', ($request->input('page', 1) - 1) * $especialidades->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $especialidade = new Especialidade();

        return view('especialidade.create', compact('especialidade'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EspecialidadeRequest $request): RedirectResponse
    {
        Especialidade::create($request->validated());

        return Redirect::route('especialidade.index')
            ->with('success', '¡Listo! La nueva especialidad ha sido creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $especialidade = Especialidade::find($id);

        return view('especialidade.show', compact('especialidade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $especialidade = Especialidade::find($id);

        return view('especialidade.edit', compact('especialidade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EspecialidadeRequest $request, Especialidade $especialidade): RedirectResponse
    {
        $especialidade->update($request->validated());

        return Redirect::route('especialidade.index')
            ->with('success', '¡Listo! La especialidad se ha actualizado con éxito.');
    }

    public function destroy($id): RedirectResponse
    {
        Especialidade::find($id)->delete();

        return Redirect::route('especialidade.index')
            ->with('success', '¡Listo! El usuario se ha eliminado con éxito.');
    }
}
