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
            $table->string('pais', 150);
            $table->string('provincia', 150);
            $table->string('distrito', 45);
            $table->string('departamento', 45);
            $table->string('lengua_materna', 45);
            $table->string('religion', 45);
            $table->char('estado_civil', 1);
            $table->timestamps();
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
