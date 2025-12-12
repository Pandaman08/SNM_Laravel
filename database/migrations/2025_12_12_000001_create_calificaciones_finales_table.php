<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('calificaciones_finales', function (Blueprint $table) {
            $table->bigIncrements('id_calificacion_final');
            $table->string('codigo_matricula');
            $table->string('codigo_asignatura');
            $table->string('calificacion_final')->nullable();
            $table->date('fecha_registro')->nullable();
            $table->timestamps();

            $table->index('codigo_matricula');
            $table->index('codigo_asignatura');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calificaciones_finales');
    }
};
