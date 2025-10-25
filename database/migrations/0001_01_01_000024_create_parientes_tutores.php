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
        Schema::create('pariente_tutor', function (Blueprint $table) {
            $table->id('id_pariente_tutor'); // Clave primaria personalizada
            $table->unsignedBigInteger('tutor_id_tutor'); // FK hacia tutor
            $table->string('nombre_pariente_tutor', 100);
            $table->string('celular_pariente_tutor', 9); // 9 dígitos

            // Clave foránea
            $table->foreign('tutor_id_tutor')
                  ->references('id_tutor')
                  ->on('tutor')
                  ->onDelete('cascade'); // o ->onDelete('restrict') según tu lógica de negocio

            $table->timestamps(); // Si deseas created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pariente_tutor');
    }
};