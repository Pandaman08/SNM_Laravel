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
        Schema::create('detalles_asignatura', function (Blueprint $table) {
            $table->id('id_detalle_asignatura');
            $table->unsignedBigInteger('id_competencias');
            $table->unsignedBigInteger('codigo_matricula');
            $table->dateTime('fecha');
            $table->string('calificacion_anual', 2)->nullable();
            $table->timestamps();

            $table->foreign('id_competencias')->references('id_competencias')->on('competencias');
            $table->foreign('codigo_matricula')->references('codigo_matricula')->on('matriculas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_asignatura');
    }
};
