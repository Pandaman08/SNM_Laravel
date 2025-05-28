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
        Schema::create('docentes', function (Blueprint $table) {
            $table->id('codigo_docente');
            $table->unsignedBigInteger('user_id');
            $table->string('especialidad', 100)->nullable();
            $table->float('jornada_laboral')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->string('departamento_estudios', 100)->nullable();
            $table->char('estado_civil', 1)->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
