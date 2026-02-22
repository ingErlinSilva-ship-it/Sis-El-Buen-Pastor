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
    public function index(Request $request)
{
    $user = auth()->user();

    // 1. LÓGICA PARA ADMIN Y DOCTOR (Ven a todos)
    if (in_array($user->rol_id, [1, 2])) {
        $pacientes = Paciente::with('usuario')->paginate();
    } 
    // 2. LÓGICA PARA EL PACIENTE (Solo se ve a sí mismo)
    else if ($user->rol_id == 3) {
        $pacientes = Paciente::where('usuario_id', $user->id)
            ->with('usuario')
            ->paginate();
    } else {
        $pacientes = collect();
    }

    return view('paciente.index', compact('pacientes'))
        ->with('i', ($request->input('page', 1) - 1) * 10);
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
    $user = auth()->user();

    // Seguridad: El paciente solo puede editar su propio perfil
    if ($user->rol_id == 3 && $paciente->usuario_id !== $user->id) {
        abort(403);
    }

    return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $paciente, $user) {
        
        // 1. Actualizamos la tabla 'pacientes' con lo que validó el Request
        // Usamos fill y save para que solo cambie lo que es diferente
        $paciente->fill($request->validated());
        $paciente->save();

        // 2. ACTUALIZACIÓN DEL USUARIO
        // Usamos array_filter para eliminar cualquier valor nulo o vacío del request
        // Así, si solo enviaste 'direccion', el nombre y apellido no se tocan.
        $datosUsuario = array_filter($request->only(['nombre', 'apellido', 'celular']));
        
        if (!empty($datosUsuario)) {
            $paciente->usuario->update($datosUsuario);
        }

        // 3. lógica de Alergias y Enfermedades
        // Solo se ejecuta si el usuario NO es paciente (Admin o Doctor)
        
            $paciente->alergias()->sync($request->alergias ?? []);
            $paciente->enfermedades()->sync($request->enfermedades ?? []);
        

        return Redirect::route('paciente.index')
            ->with('success', '¡Listo! Los datos se han actualizado con éxito.');
    });
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
            $paciente = \App\Models\Paciente::with(['usuario', 'consultas.cita', 'alergias', 'enfermedades'])->findOrFail($id);

            // Calculamos la edad dinámicamente
            $edad = $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age . ' años' : 'No registrada';

            // 1. Construimos una ficha técnica CRUDA (sin adornos)
            $fichaCientifica = "INSTRUCCIÓN: Genera un resumen clínico ejecutivo. \n";
            $fichaCientifica .= "DATOS PERSONALES: \n";
            $fichaCientifica .= "- Nombre: {$paciente->usuario->nombre} {$paciente->usuario->apellido} \n";
            $fichaCientifica .= "- Edad: {$edad} \n";
            $fichaCientifica .= "- Cédula: {$paciente->cedula} \n";
            $fichaCientifica .= "- Tipo de Sangre: {$paciente->tipo_sangre} \n\n";

            $fichaCientifica .= "ANTECEDENTES: \n";
            $fichaCientifica .= "- Alergias: " . ($paciente->alergias->pluck('nombre')->implode(', ') ?: 'Ninguna registrada') . " \n";
            $fichaCientifica .= "- Enfermedades Crónicas: " . ($paciente->enfermedades->pluck('nombre')->implode(', ') ?: 'Ninguna registrada') . " \n\n";

            $fichaCientifica .= "HISTORIAL DE CONSULTAS: \n";
            if ($paciente->consultas->isEmpty()) {
                $fichaCientifica .= "- Sin registros de consultas previas.";
            } else {
                foreach ($paciente->consultas as $c) {
                    $fecha = $c->cita ? $c->cita->fecha : 'N/A';
                    $fichaCientifica .= "* Fecha: {$fecha} | Dx: {$c->diagnostico} | Tto: {$c->prescripcion} \n";
                }
            }

            // 2. Le pedimos que SEA BREVE Y MÉDICO
            $instruccionFinal = "Analiza los datos anteriores. Si no hay consultas, enfócate en los antecedentes y datos personales. No saludes, no digas 'Soy el asistente virtual', solo entrega el resumen médico estructurado.";

            // Enviar a Render
            $response = \Illuminate\Support\Facades\Http::post(env('CHATBOT_API_URL'), [
                'mensaje' => $fichaCientifica . "\n\n" . $instruccionFinal,
                'session_id' => 'expediente_' . $id 
            ]);

            if ($response->successful()) {
                return response()->json(['resumen' => $response->json()['respuesta']]);
            }

            return response()->json(['resumen' => 'Servidor ocupado, reintente en breve.']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}