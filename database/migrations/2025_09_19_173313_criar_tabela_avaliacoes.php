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
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id('ID');
            $table->string('aToken')->unique();
            $table->unsignedBigInteger('nIdEmpresa');
            $table->unsignedBigInteger('nIdAtendente');
            $table->string('aNomeCliente')->nullable();
            $table->string('aEmailCliente')->nullable();
            $table->integer('nNota')->nullable(); // Nota de 1 a 5
            $table->text('aComentario')->nullable();
            $table->timestamp('dAvaliadoEm')->nullable();
            $table->timestamps();
            
            $table->foreign('nIdEmpresa')->references('ID')->on('empresas')->onDelete('cascade');
            $table->foreign('nIdAtendente')->references('ID')->on('atendentes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
