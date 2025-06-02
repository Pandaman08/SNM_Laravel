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
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_competencias')->after('id_grado')->nullable();
            
            $table->foreign('id_competencias')
                  ->references('id_competencias')
                  ->on('competencias')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->dropForeign(['id_competencias']);
            $table->dropColumn('id_competencias');
        });
    }
};
