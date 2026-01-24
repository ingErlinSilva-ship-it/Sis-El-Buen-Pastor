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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            // Relaciones principales
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('medico_id')->constrained('medicos');
            $table->foreignId('cita_id')->constrained('citas'); // De aquí jalamos el motivo original

            // Información Clínica
            $table->text('sintomas');        
            $table->text('diagnostico');     // Resultado de la evaluación
            $table->text('prescripcion');    // Receta médica

            // Signos Vitales 
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('estatura', 3, 2)->nullable();
            $table->string('presion_arterial')->nullable();
            $table->decimal('temperatura', 4, 2)->nullable();
            $table->integer('frecuencia_cardiaca')->nullable();
            
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
