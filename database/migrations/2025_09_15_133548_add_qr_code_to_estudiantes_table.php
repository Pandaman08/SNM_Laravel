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
        Schema::table('estudiantes', function (Blueprint $table) {
            if (!Schema::hasColumn('estudiantes', 'qr_code')) {
                $table->string('qr_code')->nullable()->after('religion');
            }
            
            if (!Schema::hasColumn('estudiantes', 'qr_generated_at')) {
                $table->timestamp('qr_generated_at')->nullable()->after('qr_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            if (Schema::hasColumn('estudiantes', 'qr_code')) {
                $table->dropColumn('qr_code');
            }
            
            if (Schema::hasColumn('estudiantes', 'qr_generated_at')) {
                $table->dropColumn('qr_generated_at');
            }
        });
    }
};