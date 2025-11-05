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
        Schema::create('secciones', function (Blueprint $table) {
            $table->id('id_seccion');
            $table->unsignedBigInteger('id_grado');
            $table->string('seccion', 120);
            $table->integer('vacantes_seccion');
            $table->boolean('estado_vacantes')->default(true);
            $table->timestamps();

            $table->foreign('id_grado')->references('id_grado')->on('grados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secciones');
    }
};
