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
            $medico = Medico::where('usuario_id', $user->id)->first();
            $data['totalPacientes'] = Paciente::count();

            if ($medico) {
                $data['totalCitas'] = Cita::where('medico_id', $medico->id)
                    ->whereDate('fecha', $hoy)
                    ->count();
            } else {
                $data['totalCitas'] = 0;
            }
            
            return view('dashboard', $data);
        }

        // --- 3. LÓGICA PARA PACIENTE ---
    elseif ($user->rol_id == 3) {
        // Para el paciente, sus totales son "1" (él mismo) o sus citas propias
        $paciente = Paciente::where('usuario_id', $user->id)->first();
        
        $data['totalPacientes'] = 1; // Él mismo
        $data['totalCitas'] = Cita::where('paciente_id', $paciente->id ?? 0)
                                   ->whereDate('fecha', $hoy)
                                   ->count();
        
        // 0 para que la vista no falle al buscar las variables
        $data['totalUsuarios'] = 0;
        $data['totalMedicos'] = 0;
        $data['totalRoles'] = 0;
        $data['totalEspecialidades'] = 0;
        $data['totalAlergias'] = 0;
        $data['totalEnfermedades'] = 0;
        
        // Enviamos su ID para que pueda ir a su expediente desde el Home
        $data['miPacienteId'] = $paciente->id ?? null;
    }

    return view('dashboard', $data);
}
}