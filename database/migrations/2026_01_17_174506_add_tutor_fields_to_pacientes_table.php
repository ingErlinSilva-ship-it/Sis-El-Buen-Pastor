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
        Schema::table('pacientes', function (Blueprint $table) {
        // 1. Hacemos la cédula del paciente opcional (para niños)
        $table->string('cedula')->nullable()->change();

        // 2. Campos nuevos para el Responsable (Tutor)
        $table->boolean('es_menor')->default(false)->after('usuario_id');
        $table->string('tutor_nombre')->nullable()->after('es_menor');
        $table->string('tutor_apellido')->nullable()->after('tutor_nombre');
        $table->string('tutor_cedula')->nullable()->after('tutor_apellido');
        $table->string('tutor_telefono')->nullable()->after('tutor_cedula');
        $table->string('tutor_parentesco')->nullable()->after('tutor_telefono');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('cedula')->nullable(false)->change();
            $table->dropColumn(['es_menor', 'tutor_nombre', 'tutor_apellido', 'tutor_cedula', 'tutor_telefono', 'tutor_parentesco']);
        });
    }
};
