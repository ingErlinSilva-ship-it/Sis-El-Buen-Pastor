<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\AlergiaController;
use App\Http\Controllers\EnfermedadeController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ConsultaController; // Tu controlador de consultas
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// --- 1. RUTAS PARA TODOS LOS LOGUEADOS ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Utilidades de citas
    Route::get('/paciente/buscar-por-cedula/{cedula}', [CitaController::class, 'buscarPorCedula']);
    Route::get('/citas/verificar-disponibilidad', [CitaController::class, 'verificarDisponibilidad']);
    // Ruta para generar el resumen con IA
    Route::get('/paciente/{paciente}/resumen-ia', [PacienteController::class, 'generarResumenIA'])->name('paciente.resumen.ia');
});

// --- 2. SOLO ADMINISTRADOR ---
Route::middleware(['auth', 'can:administrador'])->group(function () {
    Route::resource('role', RoleController::class);
    Route::resource('usuario', UsuarioController::class);
    Route::resource('especialidade', EspecialidadeController::class);
    Route::resource('medico', MedicoController::class);
    
});

// --- 3. SOLO DOCTORES (Aquí integramos tus rutas de Consultas) ---
Route::middleware(['auth', 'can:doctor'])->group(function () {
    Route::resource('alergia', AlergiaController::class);
    Route::resource('enfermedade', EnfermedadeController::class);
    Route::resource('paciente', PacienteController::class);
    
    // TUS RUTAS: Ahora protegidas para que solo el doctor atienda y gestione consultas
    Route::resource('consulta', ConsultaController::class);
    Route::get('/consultas/atender/{cita_id}', [ConsultaController::class, 'atender'])->name('consultas.atender');
});

// --- 4. GESTIÓN DE CITAS ---
Route::middleware(['auth'])->group(function () {
    Route::resource('cita', CitaController::class);
});

require __DIR__.'/auth.php';