<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            // Primero agregar justificacion si no existe
            if (!Schema::hasColumn('asistencias', 'justificacion')) {
                $table->text('justificacion')->nullable()->after('observacion');
            }
            
            // Luego los demás campos
            $table->enum('estado_justificacion', ['pendiente', 'aprobada', 'rechazada'])->nullable()->after('justificacion');
            $table->text('motivo_rechazo')->nullable()->after('estado_justificacion');
            $table->timestamp('fecha_solicitud_justificacion')->nullable()->after('motivo_rechazo');
            $table->timestamp('fecha_revision_justificacion')->nullable()->after('fecha_solicitud_justificacion');
            $table->unsignedBigInteger('revisado_por')->nullable()->after('fecha_revision_justificacion');
            
            $table->foreign('revisado_por')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropForeign(['revisado_por']);
            $table->dropColumn([
                'justificacion',
                'estado_justificacion',
                'motivo_rechazo',
                'fecha_solicitud_justificacion',
                'fecha_revision_justificacion',
                'revisado_por'
            ]);
        });
    }
};