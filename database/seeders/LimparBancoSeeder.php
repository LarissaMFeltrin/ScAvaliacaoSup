<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Atendente;
use App\Models\Avaliacao;

class LimparBancoSeeder extends Seeder
{
    public function run(): void
    {
        echo "🧹 Limpando banco de dados...\n";
        
        // Desabilitar verificação de foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Limpar todas as tabelas
        DB::table('avaliacoes')->truncate();
        echo "   ✅ Tabela 'avaliacoes' limpa\n";
        
        DB::table('atendentes')->truncate();
        echo "   ✅ Tabela 'atendentes' limpa\n";
        
        DB::table('usuarios')->truncate();
        echo "   ✅ Tabela 'usuarios' limpa\n";
        
        DB::table('empresas')->truncate();
        echo "   ✅ Tabela 'empresas' limpa\n";
        
        // Reabilitar verificação de foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        echo "\n📝 Criando dados essenciais...\n";
        
        // Criar empresa TechSupport Ltda (necessária para o atendente)
        $empresa = Empresa::create([
            'aNome' => 'TechSupport Ltda',
            'aEmail' => 'contato@techsupportltda.com',
            'aTelefone' => '(44) 3333-3333',
            'aEndereco' => 'Rua da Tecnologia, 123',
            'nStatus' => 0
        ]);
        echo "   ✅ Empresa 'TechSupport Ltda' criada (ID: {$empresa->ID})\n";
        
        // Criar os 3 usuários especificados
        $usuarios = [
            [
                'aNome' => 'Administrador Sistema',
                'aEmail' => 'admin@scordon.com.br',
                'aSenha' => '123456',
                'aRole' => 'admin',
                'nIdEmpresa' => null,
                'nStatus' => 0
            ],
            [
                'aNome' => 'Suporte Técnico',
                'aEmail' => 'suporte@scordon.com.br',
                'aSenha' => '123456',
                'aRole' => 'suporte',
                'nIdEmpresa' => null,
                'nStatus' => 0
            ],
            [
                'aNome' => 'Atendente TechSupport',
                'aEmail' => 'atendente1@techsupportltda.com',
                'aSenha' => '123456',
                'aRole' => 'atendente',
                'nIdEmpresa' => $empresa->ID,
                'nStatus' => 0
            ]
        ];
        
        foreach ($usuarios as $dadosUsuario) {
            $usuario = Usuario::create($dadosUsuario);
            echo "   ✅ Usuário '{$usuario->aEmail}' criado (Role: {$usuario->aRole})\n";
        }
        
        // Criar um atendente na tabela atendentes para o usuário atendente
        $atendenteUsuario = Usuario::where('aEmail', 'atendente1@techsupportltda.com')->first();
        $atendente = Atendente::create([
            'nIdEmpresa' => $empresa->ID,
            'aNome' => $atendenteUsuario->aNome,
            'aEmail' => $atendenteUsuario->aEmail,
            'nStatus' => 0
        ]);
        echo "   ✅ Atendente '{$atendente->aNome}' criado na tabela atendentes\n";
        
        echo "\n🎉 Banco limpo e configurado com sucesso!\n";
        echo "\n📋 USUÁRIOS DISPONÍVEIS (senha: 123456):\n";
        echo "   🔑 admin@scordon.com.br (Administrador)\n";
        echo "   🔑 suporte@scordon.com.br (Suporte)\n";
        echo "   🔑 atendente1@techsupportltda.com (Atendente)\n";
        echo "\n💡 Agora você pode gerar links de avaliação e testar o sistema!\n";
    }
}