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
use App\Http\Controllers\ConsultaController; 
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    
// --- 1. RUTAS PARA TODOS LOS LOGUEADOS ---
Route::resource('usuario', UsuarioController::class);
Route::resource('medico', MedicoController::class);
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Utilidades de citas
    Route::get('/paciente/buscar-por-cedula/{cedula}', [CitaController::class, 'buscarPorCedula']);
    Route::get('/citas/verificar-disponibilidad', [CitaController::class, 'verificarDisponibilidad']);
    
    // Ruta para generar el resumen con IA
    Route::get('/paciente/{paciente}/resumen-ia', [PacienteController::class, 'generarResumenIA'])->name('paciente.resumen.ia');
    
    // Rutas para la generación de PDFs
    Route::post('/consulta/{id}/descargar-receta', [ConsultaController::class, 'descargarReceta'])->name('consulta.descargar_receta');
    Route::get('/consulta/{id}/pdf-completo', [ConsultaController::class, 'pdfCompleto'])->name('consulta.pdf_completo');
});

// --- 2. SOLO ADMINISTRADOR (Y GESTIÓN GLOBAL) ---
Route::middleware(['auth', 'can:administrador'])->group(function () {
    Route::resource('role', RoleController::class);
    Route::resource('especialidade', EspecialidadeController::class);
});

// --- 3. ACCESO COMPARTIDO Y PACIENTE ---
Route::middleware(['auth'])->group(function () {
    // Rutas exclusivas para Doctores y Administradores
    Route::middleware(['can:doctor-o-administrador'])->group(function () {
        Route::resource('alergia', AlergiaController::class);
        Route::resource('enfermedade', EnfermedadeController::class);
        Route::resource('consulta', ConsultaController::class);
        Route::get('/consultas/atender/{cita_id}', [ConsultaController::class, 'atender'])->name('consultas.atender');
    });

    // Esta ruta queda disponible para TODOS (incluyendo Pacientes)
    // El controlador PacienteController@index se encarga de filtrar para que el 
    // paciente solo se vea a sí mismo, y el Admin vea a todos.
    Route::resource('paciente', PacienteController::class);
});
    
// --- 4. GESTIÓN DE CITAS ---
Route::middleware(['auth'])->group(function () {
    Route::resource('cita', CitaController::class);
});
require __DIR__.'/auth.php';