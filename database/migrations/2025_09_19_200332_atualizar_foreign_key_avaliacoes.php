<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            // Remover foreign key antiga (atendentes)
            $table->dropForeign(['nIdAtendente']);
            
            // Adicionar nova foreign key (usuarios)
            $table->foreign('nIdAtendente')->references('ID')->on('usuarios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            // Reverter para foreign key antiga se necessário
            $table->dropForeign(['nIdAtendente']);
            $table->foreign('nIdAtendente')->references('ID')->on('atendentes')->onDelete('cascade');
        });
    }
};