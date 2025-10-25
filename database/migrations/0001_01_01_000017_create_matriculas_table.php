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
            $table->string('institucion_educativa_codigo_modular', 7);
            $table->id('codigo_matricula');
            $table->unsignedBigInteger('estudiante_codigo_estudiante');
            $table->unsignedBigInteger('anio_escolar_id_anio_escolar');
            $table->unsignedBigInteger('tipo_matricula_id_tipo_matricula');
            $table->unsignedBigInteger('seccion_id_seccion');
            $table->dateTime('fecha');
            $table->enum('estado', ['Pendiente', 'Rechazado', 'Activo', 'Finalizado'])->default('Pendiente');
            $table->timestamps();

            $table->foreign('institucion_educativa_codigo_modular')
                ->references('codigo_modular')
                ->on('institucion_educativa')
                ->onDelete('cascade');
                
            $table->foreign('estudiante_codigo_estudiante')
                ->references('codigo_estudiante')
                ->on('estudiantes')
                ->onDelete('cascade');
                
            $table->foreign('anio_escolar_id_anio_escolar')
                ->references('id_anio_escolar')
                ->on('anios_escolares')
                ->onDelete('cascade');
                
            $table->foreign('tipo_matricula_id_tipo_matricula')
                ->references('id_tipo_matricula')
                ->on('tipos_matricula')
                ->onDelete('cascade');
                
            $table->foreign('seccion_id_seccion')
                ->references('id_seccion')
                ->on('secciones')
                ->onDelete('cascade');
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