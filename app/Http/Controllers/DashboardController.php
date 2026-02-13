<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Cita;
use App\Models\Alergia;
use App\Models\Enfermedade;
use App\Models\Especialidade;
use App\Models\Role;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function index()
{
    $user = auth()->user();
    $data = [];
    $hoy = Carbon::today();

    // --- 1. LÓGICA PARA ADMINISTRADOR ---
    if ($user->rol_id == 1) { 
        $data['totalPacientes'] = Paciente::count();
        $data['totalCitas'] = Cita::whereDate('fecha', $hoy)->count();
        $data['totalUsuarios'] = User::count();
        $data['totalMedicos'] = Medico::count();
        $data['totalRoles'] = Role::count();
        $data['totalEspecialidades'] = Especialidade::count();
        $data['totalAlergias'] = Alergia::count();
        $data['totalEnfermedades'] = Enfermedade::count();
        
        return view('dashboard', $data);
    }

    // --- 2. LÓGICA PARA DOCTOR ---
    elseif ($user->rol_id == 2) {
        // Buscamos el registro de médico asociado a este usuario
        $medico = Medico::where('usuario_id', $user->id)->first();

            $data['miMedicoId'] = $medico->id ?? null;

        if ($medico) {
            // Citas de hoy para este doctor
            $data['citasHoy'] = Cita::where('medico_id', $medico->id)
                ->whereDate('fecha', $hoy)
                ->where('estado', '!=', 'cancelada')
                ->with('paciente.usuario') // Cargamos relaciones para evitar consultas lentas
                ->orderBy('hora', 'asc')
                ->get();

            $data['totalCitasHoy'] = $data['citasHoy']->count();
            
            // Total histórico de este médico
            $data['totalHistorico'] = Cita::where('medico_id', $medico->id)->count();
        } else {
            $data['citasHoy'] = collect();
            $data['totalCitasHoy'] = 0;
            $data['totalHistorico'] = 0;
        }

        return view('dashboard_doctor', $data);
    }

    // --- 3. LÓGICA PARA PACIENTE ---
    elseif ($user->rol_id == 3) {
        $paciente = Paciente::where('usuario_id', $user->id)->first();
        
        // Obtenemos su próxima cita (si tiene)
        $data['proximaCita'] = Cita::where('paciente_id', $paciente->id ?? 0)
                                    ->where('fecha', '>=', $hoy)
                                    ->where('estado', '!=', 'cancelada')
                                    ->orderBy('fecha', 'asc')
                                    ->first();

        $data['totalCitas'] = Cita::where('paciente_id', $paciente->id ?? 0)->count();
        $data['miPacienteId'] = $paciente->id ?? null;

        // Retornamos una vista especial para el paciente
        return view('dashboard_paciente', $data);
    }

    return view('dashboard', $data);
}
}