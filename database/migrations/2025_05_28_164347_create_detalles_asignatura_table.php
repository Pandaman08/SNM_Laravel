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
            $table->unsignedBigInteger('codigo_asignatura');
            $table->unsignedBigInteger('codigo_matricula');
            $table->dateTime('fecha');
            $table->timestamps();

            $table->foreign('codigo_asignatura')->references('codigo_asignatura')->on('asignaturas');
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
