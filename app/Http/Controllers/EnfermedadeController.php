<?php

namespace App\Http\Controllers;

use App\Models\Enfermedade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EnfermedadeRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EnfermedadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $enfermedades = Enfermedade::paginate();

        return view('enfermedade.index', compact('enfermedades'))
            ->with('i', ($request->input('page', 1) - 1) * $enfermedades->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $enfermedade = new Enfermedade();

        return view('enfermedade.create', compact('enfermedade'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnfermedadeRequest $request): RedirectResponse
    {
        Enfermedade::create($request->validated());

        return Redirect::route('enfermedade.index')
            ->with('success', '¡Listo! Enfermedad Registrada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $enfermedade = Enfermedade::find($id);

        return view('enfermedade.show', compact('enfermedade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $enfermedade = Enfermedade::find($id);

        return view('enfermedade.edit', compact('enfermedade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EnfermedadeRequest $request, Enfermedade $enfermedade): RedirectResponse
    {
        $enfermedade->update($request->validated());

        return Redirect::route('enfermedade.index')
            ->with('success', '¡Listo! Los Datos se Actualizaron con éxito.');
    }

    public function destroy($id): RedirectResponse
    {
        Enfermedade::find($id)->delete();

        return Redirect::route('enfermedade.index')
            ->with('success', '¡Listo! Enfermedad Eliminada con éxito.');
    }
}
