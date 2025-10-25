<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. qwe
     */
    public function up(): void
    {
        Schema::create('competencias', function (Blueprint $table) {
            $table->id('id_competencias');
            $table->unsignedBigInteger('codigo_asignatura');
            $table->string('descripcion', 150);
            $table->timestamps();

            $table->foreign('codigo_asignatura')->references('codigo_asignatura')->on('asignaturas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competencias');
    }
};
