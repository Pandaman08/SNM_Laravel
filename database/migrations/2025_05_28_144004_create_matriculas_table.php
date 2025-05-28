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
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id('codigo_matricula');
            $table->unsignedBigInteger('codigo_estudiante');
            $table->unsignedBigInteger('id_anio_escolar');
            $table->unsignedBigInteger('id_tipo_matricula');
            $table->dateTime('fecha');
            $table->timestamps();

            $table->foreign('codigo_estudiante')->references('codigo_estudiante')->on('estudiantes');
            $table->foreign('id_anio_escolar')->references('id_anio_escolar')->on('anios_escolares');
            $table->foreign('id_tipo_matricula')->references('id_tipo_matricula')->on('tipos_matricula');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
