<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
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
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Capturamos las fechas del buscador (si existen)
        $fecha_inicio = $request->get('fecha_inicio');
        $fecha_fin = $request->get('fecha_fin');

        // Iniciamos la consulta base con relaciones para que sea más rápido
        $query = Cita::with(['paciente.usuario', 'medico.usuario']);

        // Filtro por rango de fechas (Si el usuario las selecciona)
        if ($fecha_inicio && $fecha_fin) {
            $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        }

        // 1. LÓGICA PARA EL ADMINISTRADOR (Ve todo ordenado)
        if ($user->rol_id == 1) {
            $citas = $query->orderBy('fecha', 'desc')
                        ->orderBy('hora', 'desc')
                        ->get();
        }
        // 2. LÓGICA PARA EL DOCTOR
        elseif ($user->rol_id == 2) {
            $medico = Medico::where('usuario_id', $user->id)->first();
            $citas = $medico 
                ? $query->where('medico_id', $medico->id)
                        ->orderBy('fecha', 'desc')
                        ->orderBy('hora', 'desc')
                        ->get() 
                : collect();
        }
        // 3. LÓGICA PARA EL PACIENTE
        elseif ($user->rol_id == 3) {
            $paciente = Paciente::where('usuario_id', $user->id)->first();
            $citas = $paciente 
                ? $query->where('paciente_id', $paciente->id)
                        ->orderBy('fecha', 'desc')
                        ->orderBy('hora', 'desc')
                        ->get() 
                : collect();
        }

        return view('cita.index', compact('citas'));
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
        $medicos = Medico::with('usuario', 'especialidade')
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
        // Buscamos todos los pacientes donde la cédula sea del titular O del tutor
        $paciente = Paciente::where('cedula', $cedula)->orWhere('tutor_cedula', $cedula)
            ->with('usuario')
            ->get();

        if ($paciente->count() > 0) {
            return response()->json([
                'status' => 'success',
                'count' => $paciente->count(),
                'data' => $paciente->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nombre' => $p->usuario->nombre,
                        'apellido' => $p->usuario->apellido,
                        'tipo' => $p->tutor_cedula ? 'Menor' : 'Adulto'
                    ];
                })
            ]);
        }

        return response()->json(['status' => 'not_found']);
    }

    public function verificarDisponibilidad(Request $request)
    {
        $query = Cita::where('medico_id', $request->medico_id)
            ->where('fecha', $request->fecha)
            ->where('hora', $request->hora);

        // Si recibimos un ID, lo excluimos de la búsqueda
        if ($request->has('cita_id') && $request->cita_id != '') {
            $query->where('id', '!=', $request->cita_id);
        }

        $existe = $query->exists();
        return response()->json(['disponible' => !$existe]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // VALIDACIÓN DINÁMICA DEL EMAIL
    $request->validate([
        'email' => $request->paciente_id
            ? 'nullable|email'
            : 'required|email|unique:usuarios,email'
    ]);

    // 1. PRE-VALIDACIÓN DE HORARIOS
    $fecha = \Carbon\Carbon::parse($request->fecha);
    $hora = $request->hora;
    $diaSemana = $fecha->dayOfWeek;

    if ($diaSemana == \Carbon\Carbon::SATURDAY) {
        return back()->withErrors(['fecha' => 'La clínica no atiende los días sábados.'])->withInput();
    }

    if ($diaSemana >= \Carbon\Carbon::MONDAY && $diaSemana <= \Carbon\Carbon::FRIDAY) {
        if ($hora < "13:30" || $hora > "17:30") {
            return back()->withErrors(['hora' => 'Horario de Lunes a Viernes: 01:30 PM - 06:00 PM.'])->withInput();
        }
    }

    if ($diaSemana == \Carbon\Carbon::SUNDAY) {
        if ($hora < "08:00" || $hora > "11:30") {
            return back()->withErrors(['hora' => 'Los Domingos la atención es solo por la mañana (08:00 - 12:00).'])->withInput();
        }
    }

    return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $hora) {

        $citaOcupada = Cita::where('medico_id', $request->medico_id)
            ->where('fecha', $request->fecha)
            ->where('hora', $hora)
            ->exists();

        if ($citaOcupada) {
            return back()
                ->withErrors(['hora' => 'El médico ya tiene una cita programada a esa hora.'])
                ->withInput();
        }

        $pacienteId = $request->paciente_id;

        if (!$pacienteId) {
            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'celular' => $request->celular,
                'password' => \Illuminate\Support\Facades\Hash::make($request->celular),
                'rol_id' => 3,
                'estado' => 1
            ]);

            $nuevoPaciente = Paciente::create([
                'usuario_id' => $usuario->id,
                'cedula' => $request->cedula_buscada,
                'fecha_nacimiento' => null,
                'tipo_sangre' => 'No definido',
            ]);

            $pacienteId = $nuevoPaciente->id;
        }

        $cita = Cita::create([
            'paciente_id' => $pacienteId,
            'medico_id' => $request->medico_id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'duracion_minutos' => $request->duracion_minutos,
            'motivo' => $request->motivo,
            'estado' => $request->estado,
            'origen' => $request->origen,
        ]);

        try {
            $cita->paciente->usuario->notify(
                new \App\Notifications\CitaConfirmada($cita)
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error(
                'Error enviando correo de cita: ' . $e->getMessage()
            );
        }

        return redirect()->route('cita.index')
            ->with('success', 'Cita Agendada Exitosamente.');
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
        // 1. Buscamos la cita existente
        $cita = Cita::findOrFail($id);

        // 2. Solo validamos horarios si la cita NO se está cancelando
        if ($request->estado !== 'cancelada') {
            $fecha = \Carbon\Carbon::parse($request->fecha);
            $hora = $request->hora;
            $diaSemana = $fecha->dayOfWeek;

            // VALIDACIONES DE HORARIO
            if ($diaSemana == \Carbon\Carbon::SATURDAY) {
                return back()->withErrors(['fecha' => 'La clínica no atiende los sábados.'])->withInput();
            }
            if ($diaSemana >= \Carbon\Carbon::MONDAY && $diaSemana <= \Carbon\Carbon::FRIDAY) {
                if ($hora < "13:29" || $hora > "17:30") {
                    return back()->withErrors(['hora' => 'Atención de Lunes a Viernes: 01:30 PM - 06:00 PM.'])->withInput();
                }
            }
            if ($diaSemana == \Carbon\Carbon::SUNDAY) {
                if ($hora < "08:00" || $hora > "11:30") {
                    return back()->withErrors(['hora' => 'Atención de Domingos: 08:00 AM - 12:00 AM.'])->withInput();
                }
            }
        }

        // 3. Proceso de Guardado (Ahora fuera del IF para que siempre se ejecute)
        try {
            return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $cita) {
                // Solo buscamos colisiones si la cita sigue activa
                if ($request->estado !== 'cancelada') {
                    $yaExiste = \App\Models\Cita::where('medico_id', $request->medico_id)
                        ->where('fecha', $request->fecha)
                        ->where('hora', $request->hora)
                        ->where('id', '!=', $cita->id)
                        ->exists();

                    if ($yaExiste) {
                        throw new \Exception("El médico ya tiene otra cita programada a esa hora.");
                    }
                }

                $cita->update($request->all());
                return redirect()->route('cita.index')->with('success', 'Cita Actualizada Exitosamente.');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['hora' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        // Bloqueo de seguridad: Si NO es administrador, no puede borrar
        if (auth()->user()->rol_id !== 1) {
            abort(403, 'No tienes permiso para eliminar citas.');
        }

        $cita = \App\Models\Cita::find($id)->delete();

        return redirect()->route('cita.index')
            ->with('success', 'Cita eliminada exitosamente');
    }
}
