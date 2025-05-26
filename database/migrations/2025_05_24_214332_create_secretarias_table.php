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
        Schema::create('secretarias', function (Blueprint $table) {
              $table->id('codigo_secretaria');
            $table->unsignedBigInteger('user_id')->unique();
        $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->date('fecha_contratacion');
            $table->enum('area_responsabilidad', ['matriculas', 'academico', 'financiero', 'general'])->default('general');
            $table->enum('jornada_laboral', ['completa', 'parcial', 'turno_mañana', 'turno_tarde'])->default('completa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secretarias');
    }
};
