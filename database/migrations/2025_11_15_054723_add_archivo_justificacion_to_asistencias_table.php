<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            // Agregar campos para almacenar archivos de justificación
            $table->string('archivo_justificacion', 500)->nullable()->after('justificacion');
            $table->string('archivo_justificacion_original', 255)->nullable()->after('archivo_justificacion');
        });
    }

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropColumn(['archivo_justificacion', 'archivo_justificacion_original']);
        });
    }
};