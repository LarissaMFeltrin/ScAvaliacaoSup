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
        Schema::table('empresas', function (Blueprint $table) {
            // Remover foreign key primeiro
            $table->dropForeign(['nIdUsuarioCriador']);
            
            // Remover índices
            $table->dropIndex(['aTipo']);
            $table->dropIndex(['aOrigem']);
            
            // Remover colunas
            $table->dropColumn([
                'aOrigem',
                'aTipo', 
                'aObservacoes',
                'nIdUsuarioCriador'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Recriar as colunas se necessário fazer rollback
            $table->string('aOrigem', 100)->nullable()->comment('Origem da empresa/lead (site, indicacao, evento, etc)')->after('aTelefone');
            $table->enum('aTipo', ['cliente', 'lead'])->default('cliente')->comment('Tipo: cliente ou lead')->after('aOrigem');
            $table->text('aObservacoes')->nullable()->comment('Observações sobre a empresa/lead')->after('aTipo');
            $table->unsignedBigInteger('nIdUsuarioCriador')->nullable()->comment('ID do usuário que criou o registro')->after('aObservacoes');
            
            // Recriar índices
            $table->index('aTipo');
            $table->index('aOrigem');
            
            // Recriar foreign key
            $table->foreign('nIdUsuarioCriador')->references('ID')->on('usuarios')->onDelete('set null');
        });
    }
};