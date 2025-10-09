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
            // Adicionar campo para identificar o tipo de avaliação
            $table->enum('aTipo', ['suporte', 'comercial'])->default('suporte')->after('aToken');
            
            // Adicionar campos específicos para avaliação comercial
            $table->integer('nAtendeuExpectativas')->nullable()->after('nNota');
            
            // Renomear campo nNota para ser mais genérico
            $table->renameColumn('nNota', 'nNotaAtendimento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            // Remover campos adicionados
            $table->dropColumn(['aTipo', 'nAtendeuExpectativas']);
            
            // Renomear de volta
            $table->renameColumn('nNotaAtendimento', 'nNota');
        });
    }
};