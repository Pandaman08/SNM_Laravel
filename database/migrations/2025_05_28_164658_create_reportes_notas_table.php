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
        Schema::create('reportes_notas', function (Blueprint $table) {
            $table->id('id_calificacion');
            $table->unsignedBigInteger('id_detalle_asignatura');
            $table->unsignedBigInteger('id_tipo_calificacion');
            $table->unsignedBigInteger('id_periodo');
            $table->mediumText('observacion')->nullable();
            $table->date('fecha_registro');
            $table->timestamps();

            $table->foreign('id_detalle_asignatura')->references('id_detalle_asignatura')->on('detalles_asignatura');
            $table->foreign('id_tipo_calificacion')->references('id_tipo_calificacion')->on('tipos_calificacion');
            $table->foreign('id_periodo')->references('id_periodo')->on('periodos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes_notas');
    }
};
