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
        Schema::table('matriculas', function (Blueprint $table) {
            $table->unsignedBigInteger('seccion_id')->after('id_tipo_matricula');
            $table->foreign('seccion_id')->references('id_seccion')->on('secciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            //
        });
    }
};
