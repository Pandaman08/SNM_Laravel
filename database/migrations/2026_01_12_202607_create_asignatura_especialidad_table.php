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
        Schema::create('asignatura_especialidad', function (Blueprint $table) {
            $table->bigInteger('codigo_asignatura');
            $table->bigInteger('id_especialidad');
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');    
            $table->primary(['codigo_asignatura', 'id_especialidad']);
            $table->index('id_especialidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignatura_especialidad');
    }
};
