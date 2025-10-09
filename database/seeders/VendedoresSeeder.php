<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendedor;

class VendedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendedores = [
            [
                'aNome' => 'João Silva',
                'aEmail' => 'joao.silva@scordon.com.br',
                'nStatus' => 0, // Ativo
            ],
            [
                'aNome' => 'Maria Santos',
                'aEmail' => 'maria.santos@scordon.com.br',
                'nStatus' => 0, // Ativo
            ],
            [
                'aNome' => 'Pedro Oliveira',
                'aEmail' => 'pedro.oliveira@scordon.com.br',
                'nStatus' => 0, // Ativo
            ],
            [
                'aNome' => 'Ana Costa',
                'aEmail' => 'ana.costa@scordon.com.br',
                'nStatus' => 0, // Ativo
            ],
            [
                'aNome' => 'Carlos Ferreira',
                'aEmail' => 'carlos.ferreira@scordon.com.br',
                'nStatus' => 0, // Ativo
            ],
            [
                'aNome' => 'Lucia Rodrigues',
                'aEmail' => 'lucia.rodrigues@scordon.com.br',
                'nStatus' => 0, // Ativo
            ],
            [
                'aNome' => 'Roberto Alves',
                'aEmail' => 'roberto.alves@scordon.com.br',
                'nStatus' => 0, // Ativo
            ],
            [
                'aNome' => 'Fernanda Lima',
                'aEmail' => 'fernanda.lima@scordon.com.br',
                'nStatus' => 0, // Ativo
            ]
        ];

        foreach ($vendedores as $vendedor) {
            Vendedor::create($vendedor);
        }
    }
}