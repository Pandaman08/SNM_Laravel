<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar campos a la tabla secciones para horarios
        Schema::table('secciones', function (Blueprint $table) {
            $table->time('hora_entrada')->default('07:30:00')->after('seccion');
            $table->time('hora_salida')->default('13:00:00')->after('hora_entrada');
        });

        // Modificar tabla asistencias para entrada/salida
        Schema::table('asistencias', function (Blueprint $table) {
            $table->time('hora_entrada')->nullable()->after('estado');
            $table->time('hora_salida')->nullable()->after('hora_entrada');
            $table->enum('tipo_registro', ['entrada', 'salida'])->default('entrada')->after('hora_salida');
        });

        // Crear tabla para auxiliares
        if (!Schema::hasTable('auxiliares')) {
            Schema::create('auxiliares', function (Blueprint $table) {
                $table->id('id_auxiliar');
                $table->unsignedBigInteger('user_id')->unique();
                $table->string('turno')->default('mañana'); // mañana, tarde, completo
                $table->timestamps();
                
                $table->foreign('user_id')
                    ->references('user_id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('secciones', function (Blueprint $table) {
            $table->dropColumn(['hora_entrada', 'hora_salida']);
        });

        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropColumn(['hora_entrada', 'hora_salida', 'tipo_registro']);
        });

        Schema::dropIfExists('auxiliares');
    }
};