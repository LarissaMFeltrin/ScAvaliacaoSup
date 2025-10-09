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
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->timestamp('dCriadoEm')->nullable()->after('created_at');
            $table->unsignedBigInteger('nIdUsuarioGerador')->nullable()->after('nIdAtendente');
            
            $table->foreign('nIdUsuarioGerador')->references('ID')->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->dropForeign(['nIdUsuarioGerador']);
            $table->dropColumn(['dCriadoEm', 'nIdUsuarioGerador']);
        });
    }
};
