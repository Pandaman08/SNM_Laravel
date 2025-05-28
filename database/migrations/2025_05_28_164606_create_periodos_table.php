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
            $table->integer('numero_periodo');
            $table->string('nombre', 45)->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_final');
            $table->enum('estado', ['No Iniciado', 'Proceso', 'Finalizado'])->default('No Iniciado');
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
