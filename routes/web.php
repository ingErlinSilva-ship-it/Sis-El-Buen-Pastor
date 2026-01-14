<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\AlergiaController;
use App\Http\Controllers\EnfermedadeController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('role', RoleController::class);
Route::resource('usuario', UsuarioController::class);
Route::resource('especialidade', EspecialidadeController::class);
Route::resource('medico', MedicoController::class);
Route::resource('alergia', AlergiaController::class);
Route::resource('enfermedade', EnfermedadeController::class);
Route::resource('paciente', PacienteController::class);
Route::resource('cita', CitaController::class);

Route::get('/paciente/buscar-por-cedula/{cedula}', [CitaController::class, 'buscarPorCedula']);

require __DIR__.'/auth.php';
