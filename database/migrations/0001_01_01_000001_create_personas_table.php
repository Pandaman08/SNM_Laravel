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
            $table->string('name', 80);
            $table->string('lastname', 150);
            $table->char('dni', 8)->unique();
            $table->char('phone', 9);
            $table->enum('sexo',['M', 'F'])->default('M');
            $table->enum('estado_civil',['S', 'C','D','V'])->default('S');
            $table->longText('photo')->nullable();
            $table->string('address', 70)->nullable();
            $table->date('fecha_nacimiento');
            $table->timestamps();
            
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
