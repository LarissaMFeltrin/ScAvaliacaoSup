<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Atualizar dados existentes se ainda houver campo nNota
        if (Schema::hasColumn('avaliacoes', 'nNota')) {
            // Copiar dados de nNota para nNotaAtendimento
            DB::statement('UPDATE avaliacoes SET nNotaAtendimento = nNota WHERE nNotaAtendimento IS NULL AND nNota IS NOT NULL');
            
            // Definir tipo padrão para registros existentes
            DB::statement('UPDATE avaliacoes SET aTipo = "suporte" WHERE aTipo IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não é necessário fazer rollback pois os dados já foram migrados
    }
};