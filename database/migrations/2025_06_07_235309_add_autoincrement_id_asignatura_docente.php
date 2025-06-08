<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asignaturas_docentes', function (Blueprint $table) {
            $table->id('id_asignatura_docente')->autoIncrement()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asignaturas_docentes', function (Blueprint $table) {
            $table->id('id_asignatura_docente')->change();
        });
    }
};
