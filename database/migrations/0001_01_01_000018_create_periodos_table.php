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
        Schema::create('periodos', function (Blueprint $table) {
            $table->id('id_periodo');
            $table->unsignedBigInteger('id_anio_escolar');
            $table->foreign('id_anio_escolar')->references('id_anio_escolar')->on('anios_escolares')->onDelete('cascade');
            $table->string('nombre', 45)->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->enum('estado', ['Proceso', 'Finalizado'])->default('Proceso');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};
