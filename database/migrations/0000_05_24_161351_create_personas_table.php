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
        Schema::create('personas', function (Blueprint $table) {
            $table->id('persona_id');
            $table->string('name', 45);
            $table->string('lastname', 150);
            $table->char('dni',8);
            $table->char('phone',9);
            $table->enum('sexo',['M', 'F'])->default('M');
            $table->enum('estado_civil',['S', 'C','D','V'])->default('S');
            $table->string('photo', 255)->nullable();
            $table->string('address', 70)->nullable();
             $table->timestamps();
            $table->date('fecha_nacimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
