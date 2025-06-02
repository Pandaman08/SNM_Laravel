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
        Schema::create('niveles_educativos', function (Blueprint $table) {
            $table->id('id_nivel_educativo');
            $table->string('nombre', 50); // 'Inicial', 'Primaria', 'Secundaria'
            $table->string('codigo', 10); // 'INI', 'PRI', 'SEC'
            $table->text('descripcion')->nullable();
            $table->integer('edad_minima')->nullable(); // Edad mínima del nivel
            $table->integer('edad_maxima')->nullable(); // Edad máxima del nivel
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveles_educativos');
    }
};