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
        Schema::create('secciones_docentes', function (Blueprint $table) {
            // Llaves foráneas como llaves primarias compuestas
            $table->unsignedBigInteger('id_seccion');
            $table->unsignedBigInteger('codigo_docente');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            
            // Definir la llave primaria compuesta
            $table->primary(['id_seccion', 'codigo_docente']);
            
            // Definir las llaves foráneas
            $table->foreign('id_seccion')->references('id_seccion')->on('secciones')->onDelete('cascade');
            $table->foreign('codigo_docente')->references('codigo_docente')->on('docentes')->onDelete('cascade');
            
            // Índices para mejorar el rendimiento
            $table->index('id_seccion');
            $table->index('codigo_docente');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secciones_docentes');
    }
};