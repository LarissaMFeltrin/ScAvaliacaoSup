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
        // Remover tabelas desnecessárias
        Schema::dropIfExists('avaliacoes_comerciais');
        Schema::dropIfExists('vendedores');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recriar tabelas se necessário (para rollback)
        Schema::create('vendedores', function (Blueprint $table) {
            $table->id('ID');
            $table->string('aNome');
            $table->string('aEmail')->nullable();
            $table->integer('nStatus')->default(0);
            $table->timestamps();
        });

        Schema::create('avaliacoes_comerciais', function (Blueprint $table) {
            $table->id('ID');
            $table->string('aToken')->unique();
            $table->unsignedBigInteger('nIdEmpresa');
            $table->unsignedBigInteger('nIdVendedor');
            $table->unsignedBigInteger('nIdUsuarioGerador');
            $table->string('aNomeCliente')->nullable();
            $table->string('aEmailCliente')->nullable();
            $table->integer('nNotaAtendimento')->nullable();
            $table->integer('nAtendeuExpectativas')->nullable();
            $table->text('aComentario')->nullable();
            $table->timestamp('dCriadoEm')->nullable();
            $table->timestamp('dAvaliadoEm')->nullable();
            $table->timestamps();
            
            $table->foreign('nIdEmpresa')->references('ID')->on('empresas')->onDelete('cascade');
            $table->foreign('nIdVendedor')->references('ID')->on('vendedores')->onDelete('cascade');
            $table->foreign('nIdUsuarioGerador')->references('ID')->on('usuarios')->onDelete('cascade');
        });
    }
};