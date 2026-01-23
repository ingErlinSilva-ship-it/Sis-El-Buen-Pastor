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
use Carbon\Carbon; // Indispensable para manejar fechas

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];
        $hoy = Carbon::today(); // Fecha actual del servidor

        // --- LÓGICA PARA ADMINISTRADOR ---
        if ($user->rol_id == 1) { // ADMINISTRADOR
        // Fila 1: Operativos
        $data['totalPacientes'] = Paciente::count();
        $data['totalCitas'] = Cita::whereDate('fecha', $hoy)->count();
        $data['totalUsuarios'] = User::count();
        $data['totalMedicos'] = Medico::count();

        // Fila 2: Paramétricos (NUEVOS)
        $data['totalRoles'] = Role::count();
        $data['totalEspecialidades'] = Especialidade::count();
        $data['totalAlergias'] = Alergia::count();
        $data['totalEnfermedades'] = Enfermedade::count();
    }

        // --- LÓGICA PARA DOCTOR ---
        elseif ($user->rol_id == 2) {
            $medico = Medico::where('usuario_id', $user->id)->first();

            // El doctor sigue viendo el total global de pacientes (como pediste)
            $data['totalPacientes'] = Paciente::count();

            if ($medico) {
                // Conteo PERSONAL de sus citas de HOY
                $data['totalCitas'] = Cita::where('medico_id', $medico->id)
                    ->whereDate('fecha', $hoy)
                    ->count();
            } else {
                $data['totalCitas'] = 0;
            }
        }

        return view('dashboard', $data);
    }
}