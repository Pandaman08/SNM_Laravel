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
        Schema::table('pagos', function (Blueprint $table) {
            $table->enum('estado', ['Pendiente', 'Finalizado', 'Rechazado'])
                  ->nullable()
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('pagos', function (Blueprint $table) {
            $table->enum('estado', ['Pendiente', 'Finalizado'])
                  ->nullable()
                  ->change();
        });
    }
};
