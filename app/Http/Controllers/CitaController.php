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
        // Buscamos todos los pacientes donde la cédula sea del titular O del tutor
        $paciente = Paciente::where('cedula', $cedula)->orWhere('tutor_cedula', $cedula)
                ->with('usuario')
                ->get();

        if ($paciente->count() > 0) {
                return response()->json([
                    'status' => 'success',
                    'count' => $paciente->count(),
                    'data' => $paciente->map(function($p) {
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
        // 1. PRE-VALIDACIÓN DE HORARIOS (Antes de entrar a la transacción)
        $fecha = \Carbon\Carbon::parse($request->fecha);
        $hora = $request->hora;
        $diaSemana = $fecha->dayOfWeek; // 0 (Dom) a 6 (Sáb)

        // Regla: Sábados no hay atención
        if ($diaSemana == \Carbon\Carbon::SATURDAY) {
            return back()->withErrors(['fecha' => 'La clínica no atiende los días sábados.'])->withInput();
        }

        // Regla: Lunes a Viernes (Solo tarde: 2:00 PM a 6:00 PM por ejemplo)
        if ($diaSemana >= \Carbon\Carbon::MONDAY && $diaSemana <= \Carbon\Carbon::FRIDAY) {
            // Si la hora es menor a las 2pm O mayor a las 6pm, da error.
            if ($hora < "13:30" || $hora > "17:30") { 
                return back()->withErrors(['hora' => 'Horario de Lunes a Viernes: 01:30 PM - 06:00 PM.'])->withInput();
            }
        }

        // Regla: Domingos (Solo mañana: 8:00 AM a 12:00 PM)
        if ($diaSemana == \Carbon\Carbon::SUNDAY) {
            if ($hora < "08:00" || $hora > "11:30") {
                return back()->withErrors(['hora' => 'Los Domingos la atención es solo por la mañana (08:00 - 12:00).'])->withInput();
            }
        }

        // 2. TRANSACCIÓN DE BASE DE DATOS
        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $hora) {
            
            // --- VALIDACIÓN DE DISPONIBILIDAD DEL MÉDICO ---
            $citaOcupada = Cita::where('medico_id', $request->medico_id)
                ->where('fecha', $request->fecha)
                ->where('hora', $hora)
                ->exists();

            if ($citaOcupada) {
                // Lanzamos excepción para cancelar la transacción si ya está ocupado
                throw new \Exception("El médico ya tiene una cita programada a esa hora.");
            }

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

            // Crear la Cita
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
            
        }, 5); // El 5 indica reintentos en caso de deadlock
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

        $fecha = \Carbon\Carbon::parse($request->fecha);
        $hora = $request->hora;
        $diaSemana = $fecha->dayOfWeek;

        // VALIDACIONES DE HORARIO SINCRONIZADAS
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

        try {
            return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id) {
                // BUSCAR COLISIÓN IGNORANDO ESTA CITA ($id)
                $yaExiste = \App\Models\Cita::where('medico_id', $request->medico_id)
                    ->where('fecha', $request->fecha)
                    ->where('hora', $request->hora)
                    ->where('id', '!=', $id) // <--- CRÍTICO: Ignora la cita que estás editando
                    ->exists();

                if ($yaExiste) {
                    throw new \Exception("El médico ya tiene otra cita programada a esa hora.");
                }

                $cita = \App\Models\Cita::findOrFail($id);
                $cita->update($request->all());

                return redirect()->route('cita.index')->with('success', 'Cita Actualizada Exitosamente.');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['hora' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id): RedirectResponse
    {
        Cita::find($id)->delete();

        return Redirect::route('cita.index')
            ->with('success', 'Cita Eliminada Exitosamente.');
    }
}
