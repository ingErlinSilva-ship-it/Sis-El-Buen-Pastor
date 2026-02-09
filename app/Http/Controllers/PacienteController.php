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
use Gemini\Laravel\Facades\Gemini;

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

        return view('paciente.create', compact('paciente', 'usuarios', 'alergias', 'enfermedades'));
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
            'tipo_sangre',
        ]));

        // GUARDAR PIVOT
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
    // Cargamos al paciente con sus alergias, enfermedades y sus consultas
    // También traemos los datos del médico que realizó cada consulta
    $paciente = Paciente::with([
        'usuario', 
        'alergias', 
        'enfermedades', 
        'consultas.medico.usuario' // Trae la consulta -> el médico -> y su nombre de usuario
    ])->findOrFail($id);

    $user = auth()->user();

    // FILTRO DE PRIVACIDAD:
    // Si el usuario es un Paciente (rol_id 3), verificamos que el expediente sea el SUYO
    if ($user->rol_id == 3) {
        if ($paciente->usuario_id !== $user->id) {
            abort(403, 'No tienes permiso para ver este expediente clínico.');
        }
    }

    return view('paciente.show', compact('paciente'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $paciente = Paciente::with(['alergias', 'enfermedades'])->findOrFail($id);
        $ID_ROL_PACIENTE = 3;

        $usuarios = Usuario::where('rol_id', $ID_ROL_PACIENTE)
            ->where(function ($query) use ($paciente) {
                $query->whereDoesntHave('paciente')
                    ->orWhere('id', $paciente->usuario_id);
            })
            ->pluck('nombre', 'id');

        $alergias = Alergia::pluck('nombre', 'id');
        $enfermedades = Enfermedade::pluck('nombre', 'id');

        return view('paciente.edit', compact('paciente', 'usuarios', 'alergias', 'enfermedades'));
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(PacienteRequest $request, Paciente $paciente): RedirectResponse
        {
            $data = $request->validated();

            $paciente->update($data);

            $paciente->alergias()->sync($request->alergias ?? []);
            $paciente->enfermedades()->sync($request->enfermedades ?? []);

            return Redirect::route('paciente.index')
                ->with('success', '¡Listo! Los datos del Paciente se han Actualizado con éxito.');
        }

    public function destroy($id)
    {
        // Solo el rol_id 1 puede borrar
        if (auth()->user()->rol_id !== 1) {
            abort(403, 'Acción no autorizada.');
        }

        Paciente::find($id)->delete();

        return redirect()->route('paciente.index')
            ->with('success', 'Paciente eliminado con éxito');
    }

    public function generarResumenIA($id) 
    {
        try {
        // 1. Buscar al paciente y sus datos
        $paciente = \App\Models\Paciente::with(['usuario', 'consultas.cita'])->findOrFail($id);

        // 2. Validación de datos (Lo que mencionaste: si está vacío, se detiene aquí)
        if ($paciente->consultas->isEmpty()) {
            return response()->json([
                'resumen' => 'No hay datos clínicos suficientes. Para generar un resumen, el paciente debe tener al menos una consulta registrada.'
            ]);
        }

        // 3. Construcción del historial para la IA
        $historialTexto = "";
        foreach ($paciente->consultas as $c) {
            $fecha = $c->cita ? $c->cita->fecha : 'N/A';
            $historialTexto .= "Fecha: {$fecha}, Diagnóstico: {$c->diagnostico}, Receta: {$c->prescripcion}. \n";
        }

        // 4. Definición del PROMPT (Aquí es donde daba el error, ahora está definido justo antes de usarse)
        $prompt = "Eres un asistente médico experto de la Clínica El Buen Pastor. Analiza el historial del paciente {$paciente->usuario->nombre} y genera un resumen profesional con: Estado actual, patrones detectados y recomendaciones. \n\n Historial:\n" . $historialTexto;

        // 5. CONFIGURACIÓN SSL Y LLAMADA (Solución al error anterior de cURL 60)
        // Usamos el cliente de Guzzle directamente para saltar la verificación SSL solo en local
        $result = \Gemini\Laravel\Facades\Gemini::geminiPro()
            ->generateContent($prompt);
        
        return response()->json(['resumen' => $result->text()]);

    } catch (\Exception $e) {
        // Si hay error de conexión o de API, aquí lo veremos
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}