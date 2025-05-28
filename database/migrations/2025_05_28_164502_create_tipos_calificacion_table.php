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
        Schema::create('tipos_calificacion', function (Blueprint $table) {
            $table->id('id_tipo_calificacion');
            $table->string('codigo', 45);
            $table->string('nombre', 80);
            $table->string('descripcion', 200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_calificacion');
    }
};
