<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicos', function (Blueprint $table) {
            $table->id();
            // CLAVE FORÁNEA a la tabla 'users' de Laravel (para autenticación)
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            
            // CLAVE FORÁNEA a la tabla 'especialidades_medicas'
            $table->foreignId('especialidad_id')->constrained('especialidades');
            
            // Campo de identificación profesional
            $table->string('codigo_minsa', 50)->unique()->nullable();
            
            // Campo de descripción o biografía
            $table->text('descripcion')->nullable();

            $table->timestamps();
            
            // Asegura que no se pueda registrar un mismo usuario como médico dos veces
            $table->unique('usuario_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicos');
    }
};
