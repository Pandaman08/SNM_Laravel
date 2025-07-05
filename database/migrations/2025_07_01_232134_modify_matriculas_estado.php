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
         Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn('estado_validacion');
             $table->enum('estado', ['pendiente', 'rechazado','activo','finalizado'])->default('pendiente');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn('estado');
            $table->boolean('estado_validacion')->default(false);
        });
    }
};
