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
        Schema::create('institucion_educativa', function (Blueprint $table) {

            $table->string('codigo_modular', 7)->primary();


            $table->unsignedBigInteger('user_id');

            $table->string('dre', 100)->nullable();
            $table->string('ugel', 100)->nullable();
            $table->string('logo_ie')->nullable();
            $table->string('nombre_colegio', 255);
            $table->string('codigo_local_ie', 6)->nullable();
            $table->string('modalidad_ie', 50)->nullable();
            $table->string('genero_ie', 50)->nullable();
            $table->string('turno_ie', 50)->nullable();
            $table->string('direccion_ie', 255);
            $table->string('departamento_ie', 100);
            $table->string('provincia_ie', 100);
            $table->string('distrito_ie', 100);

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('restrict'); // o 'cascade' según tu lógica

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institucion_educativa');
    }
};