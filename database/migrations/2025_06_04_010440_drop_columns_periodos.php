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
        Schema::table('periodos', function (Blueprint $table) {
            $table->dropColumn('numero_periodo');
            $table->dropColumn('fecha_inicio');
            $table->dropColumn('fecha_final');
            $table->dropColumn('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('periodos', function (Blueprint $table) {
         $table->integer('numero_periodo');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_final');
            $table->enum('estado', ['No Iniciado', 'Proceso', 'Finalizado'])->default('No Iniciado');
              });
    }
};
