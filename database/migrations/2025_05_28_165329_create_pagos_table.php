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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->unsignedBigInteger('codigo_matricula');
            $table->string('concepto', 100)->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            $table->string('fecha_pago', 45);
            $table->longText('comprobante_img')->nullable();
            $table->enum('estado', [ 'Pendiente', 'Finalizado'])->nullable();
            $table->timestamps();

            $table->foreign('codigo_matricula')->references('codigo_matricula')->on('matriculas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
