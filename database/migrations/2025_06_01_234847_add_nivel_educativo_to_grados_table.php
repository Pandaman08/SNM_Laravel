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
        Schema::table('grados', function (Blueprint $table) {
            // Agregar FK a nivel_educativo después del id_grado
            $table->unsignedBigInteger('nivel_educativo_id')->after('id_grado');
            
            // Crear FK constraint
            $table->foreign('nivel_educativo_id')
                  ->references('id_nivel_educativo')
                  ->on('niveles_educativos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grados', function (Blueprint $table) {
            // Eliminar FK primero
            $table->dropForeign(['nivel_educativo_id']);
            // Luego eliminar columna
            $table->dropColumn('nivel_educativo_id');
        });
    }
};