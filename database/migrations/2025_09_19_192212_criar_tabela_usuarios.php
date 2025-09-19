<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('ID');
            $table->string('aNome');
            $table->string('aEmail')->unique();
            $table->timestamp('dEmailVerificadoEm')->nullable();
            $table->string('aSenha');
            $table->enum('aRole', ['admin', 'suporte', 'atendente'])->default('atendente');
            $table->unsignedBigInteger('nIdEmpresa')->nullable();
            $table->integer('nStatus')->default(0); // 0 = ativo, 99 = inativo
            $table->rememberToken();
            $table->timestamps();
            
            $table->foreign('nIdEmpresa')->references('ID')->on('empresas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};