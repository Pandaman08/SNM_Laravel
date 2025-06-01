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
        Schema::create('asignaturas_docentes', function (Blueprint $table) {
            $table->string('id_asignatura_docente', 45)->primary();
            $table->unsignedBigInteger('codigo_asignatura');
            $table->unsignedBigInteger('codigo_docente');
            $table->date('fecha');
            $table->timestamps();

            $table->foreign('codigo_asignatura')->references('codigo_asignatura')->on('asignaturas');
            $table->foreign('codigo_docente')->references('codigo_docente')->on('docentes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaturas_docentes');
    }
};
