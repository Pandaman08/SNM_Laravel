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
        Schema::create('grados', function (Blueprint $table) {
            $table->id('id_grado');
            $table->unsignedBigInteger('nivel_educativo_id');
            $table->integer('grado');
            $table->timestamps();
            
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
        Schema::dropIfExists('grados');
    }
};
