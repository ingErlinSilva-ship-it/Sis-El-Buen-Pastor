<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Usuario;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CitaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $citas = Cita::paginate();

        return view('cita.index', compact('citas'))
            ->with('i', ($request->input('page', 1) - 1) * $citas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ID_ROL_MEDICO = 2; 
        $cita = new Cita();

        // IMPORTANTE: Usamos get() y luego map() para estructurar los datos
        // IMPORTANTE: Buscamos en la tabla 'medicos' directamente para obtener SU ID real
        $medicos = \App\Models\Medico::with('usuario', 'especialidade')
            ->get()
            ->map(function ($medico) {
                return [
                    'id' => $medico->id, // Este es el ID de la tabla MEDICOS
                    'nombre' => $medico->usuario->nombre . ' ' . $medico->usuario->apellido,
                    'especialidad' => $medico->especialidade->nombre ?? 'Sin especialidad'
                ];
            })->toArray();

                return view('cita.create', compact('cita', 'medicos'));
        }

    public function buscarPorCedula($cedula)
    {
        // Buscamos el paciente por cédula y traemos su relación con usuario
        $paciente = Paciente::where('cedula', $cedula)->with('usuario')->first();

        if ($paciente) {
            return response()->json([
                'status' => 'success',
                'id' => $paciente->id,
                'nombre' => $paciente->usuario->nombre,
                'apellido' => $paciente->usuario->apellido,
            ]);
        }

        return response()->json(['status' => 'not_found']);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
        $pacienteId = $request->paciente_id;

        // Registro Rápido: Si no hay paciente_id, se crea el Usuario y el Paciente
        if (!$pacienteId) {
            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email ?? $request->cedula_buscada . '@clinica.com',
                'celular' => $request->celular,
                'password' => \Illuminate\Support\Facades\Hash::make($request->celular),
                'rol_id' => 3, 
                'estado' => 1
            ]);

            $nuevoPaciente = Paciente::create([
                'usuario_id' => $usuario->id,
                'cedula' => $request->cedula_buscada,
                'tipo_sangre' => 'No definido', 
            ]);

            $pacienteId = $nuevoPaciente->id;
        }

        // Crear la Cita vinculando al paciente (nuevo o existente) y al médico
        Cita::create([
            'paciente_id' => $pacienteId,
            'medico_id' => $request->medico_id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'duracion_minutos' => $request->duracion_minutos,
            'motivo' => $request->motivo,
            'estado' => $request->estado,
            'origen' => $request->origen,
        ]);

        return redirect()->route('cita.index')->with('success', 'Cita Agendada Exitosamente.');
    });
}

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cita = Cita::find($id);

        return view('cita.show', compact('cita'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        // Cargamos la cita con sus relaciones para saber quién es el paciente y médico actual
        $cita = Cita::with(['paciente.usuario', 'medico.usuario', 'medico.especialidade'])->findOrFail($id);

        // Cargamos los médicos en el formato de array que espera el form.blade
        $medicos = \App\Models\Medico::with(['usuario', 'especialidade'])
            ->get()
            ->map(function ($medico) {
                return [
                    'id' => $medico->id,
                    'nombre' => $medico->usuario->nombre . ' ' . $medico->usuario->apellido,
                    'especialidad' => $medico->especialidade->nombre ?? 'Sin especialidad'
                ];
            })->toArray();

        return view('cita.edit', compact('cita', 'medicos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CitaRequest $request, $id): RedirectResponse
    {
        // 1. Buscamos la cita existente por ID para evitar duplicados
        $cita = Cita::findOrFail($id);

        // 2. Llenamos con los datos del formulario y guardamos
        // Esto actualizará la fila actual en la base de datos
        $cita->update($request->all());

        return Redirect::route('cita.index')
        ->with('success', 'Cita Actualizada Exitosamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Cita::find($id)->delete();

        return Redirect::route('cita.index')
            ->with('success', 'Cita Eliminada Exitosamente.');
    }
}
