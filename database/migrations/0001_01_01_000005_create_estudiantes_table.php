<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->unsignedBigInteger('codigo_estudiante')->primary();
            $table->unsignedBigInteger('persona_id');
            $table->string('pais', 150);
            $table->string('provincia', 150);
            $table->string('distrito', 45);
            $table->string('departamento', 45);
            $table->string('lengua_materna', 45);
            $table->string('religion', 45);
            $table->string('qr_code')->nullable();
            $table->timestamp('qr_generated_at')->nullable();
            $table->timestamps();

            $table->foreign('persona_id')
                ->references('persona_id')
                ->on('personas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};