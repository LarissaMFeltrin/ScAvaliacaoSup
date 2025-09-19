<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Empresa;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Admin geral (sem empresa específica)
        Usuario::create([
            'aNome' => 'Administrador Sistema',
            'aEmail' => 'admin@scordon.com.br',
            'aSenha' => '123456', // Será hasheada automaticamente
            'aRole' => 'admin',
            'nIdEmpresa' => null,
            'nStatus' => 0
        ]);

        // Usuário de suporte geral
        Usuario::create([
            'aNome' => 'Suporte Técnico',
            'aEmail' => 'suporte@scordon.com.br',
            'aSenha' => '123456',
            'aRole' => 'suporte',
            'nIdEmpresa' => null,
            'nStatus' => 0
        ]);

        // Obter empresas para criar usuários específicos
        $empresas = Empresa::all();

        foreach ($empresas as $empresa) {
            // Gerente/Admin da empresa
            Usuario::create([
                'aNome' => "Gerente {$empresa->aNome}",
                'aEmail' => "gerente@" . strtolower(str_replace(' ', '', $empresa->aNome)) . ".com",
                'aSenha' => '123456',
                'aRole' => 'admin',
                'nIdEmpresa' => $empresa->ID,
                'nStatus' => 0
            ]);

            // Atendentes da empresa
            Usuario::create([
                'aNome' => "Atendente 1 {$empresa->aNome}",
                'aEmail' => "atendente1@" . strtolower(str_replace(' ', '', $empresa->aNome)) . ".com",
                'aSenha' => '123456',
                'aRole' => 'atendente',
                'nIdEmpresa' => $empresa->ID,
                'nStatus' => 0
            ]);

            Usuario::create([
                'aNome' => "Atendente 2 {$empresa->aNome}",
                'aEmail' => "atendente2@" . strtolower(str_replace(' ', '', $empresa->aNome)) . ".com",
                'aSenha' => '123456',
                'aRole' => 'atendente',
                'nIdEmpresa' => $empresa->ID,
                'nStatus' => 0
            ]);
        }

        echo "Usuários criados com sucesso!\n";
        echo "Logins disponíveis (senha: 123456):\n";
        echo "- admin@scordon.com.br (Admin Geral)\n";
        echo "- suporte@scordon.com.br (Suporte)\n";
        
        foreach ($empresas as $empresa) {
            $domain = strtolower(str_replace(' ', '', $empresa->aNome));
            echo "- gerente@{$domain}.com (Admin da {$empresa->aNome})\n";
            echo "- atendente1@{$domain}.com (Atendente da {$empresa->aNome})\n";
            echo "- atendente2@{$domain}.com (Atendente da {$empresa->aNome})\n";
        }
    }
}