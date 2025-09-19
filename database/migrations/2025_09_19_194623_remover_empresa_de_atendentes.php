<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atendentes', function (Blueprint $table) {
            // Remover foreign key e coluna nIdEmpresa
            $table->dropForeign(['nIdEmpresa']);
            $table->dropColumn('nIdEmpresa');
        });
    }

    public function down(): void
    {
        Schema::table('atendentes', function (Blueprint $table) {
            // Restaurar coluna e foreign key se necessário
            $table->unsignedBigInteger('nIdEmpresa')->after('ID');
            $table->foreign('nIdEmpresa')->references('ID')->on('empresas')->onDelete('cascade');
        });
    }
};