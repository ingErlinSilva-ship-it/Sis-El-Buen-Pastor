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
        Schema::create('citas', function (Blueprint $table) {

            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('medico_id')->constrained('medicos')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora');
            $table->integer('duracion_minutos')->default(30); // Para evitar choques de horario
            $table->text('motivo')->nullable();
            
            // Control del Chatbot y Estado
            // 'pendiente', 'confirmada', 'asistida', 'cancelada'
            $table->enum('estado', ['pendiente', 'confirmada', 'asistida', 'cancelada'])->default('pendiente');
            
            // 'chatbot', 'presencial', 'web'
            $table->string('origen')->default('presencial'); 
            
            // ID único del chat (WhatsApp) para que el bot reconozca al usuario
            $table->string('chat_session_id')->nullable(); 
            
            // Token para que el paciente confirme vía link si es necesario
            $table->string('token_confirmacion')->nullable(); 

            $table->timestamps();
            
            // Índice para acelerar la búsqueda de disponibilidad
            $table->index(['medico_id', 'fecha', 'hora']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
