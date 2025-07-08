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
        Schema::table('detalles_asignatura', function (Blueprint $table) {
            $table->dropForeign(['codigo_asignatura']);
            $table->dropColumn('codigo_asignatura');

            // Agrega la nueva columna y su clave foránea
            $table->unsignedBigInteger('id_competencias')->after('id_detalle_asignatura');
            $table->foreign('id_competencias')->references('id_competencias')->on('competencias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalles_asignatura', function (Blueprint $table) {
             $table->dropForeign(['id_competencias']);
            $table->dropColumn('id_competencias');

            // Restaura la columna y clave foránea anterior
            $table->unsignedBigInteger('codigo_asignatura')->after('id_detalle_asignatura');
            $table->foreign('codigo_asignatura')->references('codigo_asignatura')->on('asignaturas');
        });
    }
};
