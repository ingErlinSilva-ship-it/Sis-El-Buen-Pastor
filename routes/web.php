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
    
    // Utilidades de citas que usan tanto Recepción/Doctor como el Paciente
    Route::get('/paciente/buscar-por-cedula/{cedula}', [CitaController::class, 'buscarPorCedula']);
    Route::get('/citas/verificar-disponibilidad', [CitaController::class, 'verificarDisponibilidad']);
});

// --- 2. SOLO ADMINISTRADOR (Y Super Poder) ---
Route::middleware(['auth', 'can:administrador'])->group(function () {
    Route::resource('role', RoleController::class);
    Route::resource('usuario', UsuarioController::class);
    Route::resource('especialidade', EspecialidadeController::class);
    Route::resource('medico', MedicoController::class);
});

// --- 3. SOLO DOCTORES (Y el Admin por Super Poder) ---
Route::middleware(['auth', 'can:doctor'])->group(function () {
    Route::resource('alergia', AlergiaController::class);
    Route::resource('enfermedade', EnfermedadeController::class);
    Route::resource('paciente', PacienteController::class);
});

// --- 4. PACIENTES Y GESTIÓN DE CITAS ---
Route::middleware(['auth'])->group(function () {
    // Si quieres que el paciente solo vea SUS citas, la lógica va dentro del controlador
    Route::resource('cita', CitaController::class);
});

require __DIR__.'/auth.php';