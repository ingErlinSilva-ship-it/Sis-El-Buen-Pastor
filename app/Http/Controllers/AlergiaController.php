<?php

namespace App\Http\Controllers;

use App\Models\Alergia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AlergiaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AlergiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $alergias = Alergia::paginate();

        return view('alergia.index', compact('alergias'))
            ->with('i', ($request->input('page', 1) - 1) * $alergias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $alergia = new Alergia();

        return view('alergia.create', compact('alergia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlergiaRequest $request): RedirectResponse
    {
        Alergia::create($request->validated());

        return Redirect::route('alergia.index')
            ->with('success', '¡Listo! Se ha agreado otraalergia con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $alergia = Alergia::find($id);

        return view('alergia.show', compact('alergia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $alergia = Alergia::find($id);

        return view('alergia.edit', compact('alergia'));
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(AlergiaRequest $request, $id): RedirectResponse
    {
        // Validamos primero
        $datos = $request->validated();
        
        // Buscamos el registro real
        $alergia = Alergia::findOrFail($id);

        // Forzamos la actualización
        $alergia->fill($datos);
        $alergia->save();

        return Redirect::route('alergia.index')
        ->with('success', '¡Listo! La alergia se ha actualizado con éxito.');
    }

    public function destroy($id): RedirectResponse
    {
        Alergia::find($id)->delete();

        return Redirect::route('alergia.index')
            ->with('success', '¡Listo! La alergia se ha eliminado con éxito.');
    }
}
