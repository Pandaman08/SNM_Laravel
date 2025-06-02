<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\UserRole;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id('codigo_estudiante');
            $table->unsignedBigInteger('persona_id');
            $table->string('pais', 150);
            $table->string('provincia', 150);
            $table->string('distrito', 45);
            $table->string('departamento', 45);
            $table->string('lengua_materna', 45);
            $table->string('religion', 45);
            $table->timestamps();
            $table->foreign('persona_id')
                ->references('persona_id')
                ->on('personas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
