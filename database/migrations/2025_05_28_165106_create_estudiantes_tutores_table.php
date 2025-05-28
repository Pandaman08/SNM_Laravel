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
        Schema::create('estudiantes_tutores', function (Blueprint $table) {
            $table->unsignedBigInteger('codigo_estudiante');
            $table->unsignedBigInteger('id_tutor');
            $table->string('tipo_relacion', 45);
            $table->timestamps();

            $table->primary(['codigo_estudiante', 'id_tutor']);
            $table->foreign('codigo_estudiante')->references('codigo_estudiante')->on('estudiantes');
            $table->foreign('id_tutor')->references('id_tutor')->on('tutores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes_tutores');
    }
};
