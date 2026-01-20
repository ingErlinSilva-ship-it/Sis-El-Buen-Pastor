<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Usuario;
use App\Models\Alergia;
use App\Models\Enfermedade;
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

        $alergias = Alergia::pluck('nombre', 'id');
        $enfermedades = Enfermedade::pluck('nombre', 'id');

        return view('paciente.create', compact('paciente','usuarios','alergias','enfermedades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PacienteRequest $request): RedirectResponse
    {

        $paciente = Paciente::create($request->only([
        'usuario_id',
        'fecha_nacimiento',
        'cedula',
        'direccion',
        'tipo_sangre',]));

        // 🔗 GUARDAR PIVOT
        if ($request->has('alergias')) {
            $paciente->alergias()->sync($request->alergias);
        }

        if ($request->has('enfermedades')) {
            $paciente->enfermedades()->sync($request->enfermedades);
        }

        return redirect()->route('paciente.index')
            ->with('success', 'Paciente creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $paciente = Paciente::with(['usuario','alergias','enfermedades'])->findOrFail($id);


        return view('paciente.show', compact('paciente'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $paciente = Paciente::with(['alergias','enfermedades'])->findOrFail($id);
        $ID_ROL_PACIENTE = 3;

        $usuarios = Usuario::where('rol_id', $ID_ROL_PACIENTE)
            ->where(function($query) use ($paciente) {
                $query->whereDoesntHave('paciente')
                    ->orWhere('id', $paciente->usuario_id);
            })
            ->pluck('nombre', 'id');

        $alergias = Alergia::pluck('nombre', 'id');
        $enfermedades = Enfermedade::pluck('nombre', 'id');

        return view('paciente.edit', compact('paciente','usuarios','alergias','enfermedades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PacienteRequest $request, Paciente $paciente): RedirectResponse
    {
        $paciente->update($request->validated());

        // 🔄 sync pivot
        $paciente->alergias()->sync($request->alergias ?? []);
        $paciente->enfermedades()->sync($request->enfermedades ?? []);

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
