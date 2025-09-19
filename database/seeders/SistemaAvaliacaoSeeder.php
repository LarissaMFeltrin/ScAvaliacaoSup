<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Atendente;
use App\Models\Avaliacao;

class SistemaAvaliacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar empresas
        $empresa1 = Empresa::create([
            'aNome' => 'TechSupport Ltda',
            'aEmail' => 'contato@techsupport.com',
            'aTelefone' => '(11) 1234-5678',
            'aEndereco' => 'Rua das Flores, 123 - São Paulo/SP',
            'nStatus' => 0
        ]);

        $empresa2 = Empresa::create([
            'aNome' => 'Atendimento Plus',
            'aEmail' => 'suporte@atendimentoplus.com',
            'aTelefone' => '(21) 9876-5432',
            'aEndereco' => 'Av. Principal, 456 - Rio de Janeiro/RJ',
            'nStatus' => 0
        ]);

        $empresa3 = Empresa::create([
            'aNome' => 'Help Desk Pro',
            'aEmail' => 'help@helpdeskpro.com',
            'aTelefone' => '(31) 5555-1234',
            'aEndereco' => 'Rua do Comércio, 789 - Belo Horizonte/MG',
            'nStatus' => 0
        ]);

        // Criar atendentes para TechSupport
        $atendentes1 = [
            Atendente::create([
                'nIdEmpresa' => $empresa1->ID,
                'aNome' => 'Maria Silva',
                'aEmail' => 'maria@techsupport.com',
                'nStatus' => 0
            ]),
            Atendente::create([
                'nIdEmpresa' => $empresa1->ID,
                'aNome' => 'João Santos',
                'aEmail' => 'joao@techsupport.com',
                'nStatus' => 0
            ]),
            Atendente::create([
                'nIdEmpresa' => $empresa1->ID,
                'aNome' => 'Ana Costa',
                'aEmail' => 'ana@techsupport.com',
                'nStatus' => 0
            ])
        ];

        // Criar atendentes para Atendimento Plus
        $atendentes2 = [
            Atendente::create([
                'nIdEmpresa' => $empresa2->ID,
                'aNome' => 'Carlos Oliveira',
                'aEmail' => 'carlos@atendimentoplus.com',
                'nStatus' => 0
            ]),
            Atendente::create([
                'nIdEmpresa' => $empresa2->ID,
                'aNome' => 'Fernanda Lima',
                'aEmail' => 'fernanda@atendimentoplus.com',
                'nStatus' => 0
            ])
        ];

        // Criar atendentes para Help Desk Pro
        $atendentes3 = [
            Atendente::create([
                'nIdEmpresa' => $empresa3->ID,
                'aNome' => 'Roberto Ferreira',
                'aEmail' => 'roberto@helpdeskpro.com',
                'nStatus' => 0
            ]),
            Atendente::create([
                'nIdEmpresa' => $empresa3->ID,
                'aNome' => 'Juliana Pereira',
                'aEmail' => 'juliana@helpdeskpro.com',
                'nStatus' => 0
            ])
        ];

        // Criar avaliações de exemplo
        $nomesClientes = [
            'Pedro Almeida', 'Carla Rodrigues', 'Lucas Martins', 'Patrícia Souza', 
            'Rafael Gomes', 'Mariana Cardoso', 'Bruno Nascimento', 'Camila Barbosa',
            'Diego Ribeiro', 'Larissa Campos', 'Thiago Moreira', 'Vanessa Torres'
        ];

        $emailsClientes = [
            'pedro@email.com', 'carla@email.com', 'lucas@email.com', 'patricia@email.com',
            'rafael@email.com', 'mariana@email.com', 'bruno@email.com', 'camila@email.com',
            'diego@email.com', 'larissa@email.com', 'thiago@email.com', 'vanessa@email.com'
        ];

        $comentarios = [
            'Atendimento excelente, muito prestativo!',
            'Resolveram meu problema rapidamente.',
            'Atendente muito educado e eficiente.',
            'Poderia ser mais rápido, mas foi bom.',
            'Não conseguiram resolver meu problema.',
            'Atendimento ok, dentro do esperado.',
            'Superou minhas expectativas!',
            'Muito satisfeito com o suporte.',
            'Atendimento demorou um pouco.',
            'Excelente profissional, recomendo!',
            'Bom atendimento, mas pode melhorar.',
            'Perfeito! Muito obrigado pela ajuda.',
            null, // Sem comentário
            null, // Sem comentário
        ];

        $todosAtendentes = array_merge($atendentes1, $atendentes2, $atendentes3);

        // Criar 50 avaliações aleatórias
        for ($i = 0; $i < 50; $i++) {
            $atendente = $todosAtendentes[array_rand($todosAtendentes)];
            $indiceCliente = array_rand($nomesClientes);
            
            // 80% das avaliações com dados do cliente, 20% anônimas
            $temDadosCliente = rand(1, 100) <= 80;
            
            Avaliacao::create([
                'nIdEmpresa' => $atendente->nIdEmpresa,
                'nIdAtendente' => $atendente->ID,
                'aNomeCliente' => $temDadosCliente ? $nomesClientes[$indiceCliente] : null,
                'aEmailCliente' => $temDadosCliente ? $emailsClientes[$indiceCliente] : null,
                'nNota' => rand(1, 5),
                'aComentario' => rand(1, 100) <= 70 ? $comentarios[array_rand($comentarios)] : null,
                'dAvaliadoEm' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59))
            ]);
        }

        // Criar algumas avaliações não respondidas (apenas tokens gerados)
        for ($i = 0; $i < 10; $i++) {
            $atendente = $todosAtendentes[array_rand($todosAtendentes)];
            
            Avaliacao::create([
                'nIdEmpresa' => $atendente->nIdEmpresa,
                'nIdAtendente' => $atendente->ID,
                'aNomeCliente' => null,
                'aEmailCliente' => null,
                'nNota' => null,
                'aComentario' => null,
                'dAvaliadoEm' => null
            ]);
        }

        $this->command->info('Dados de exemplo criados com sucesso!');
        $this->command->info('Empresas: 3');
        $this->command->info('Atendentes: 7');
        $this->command->info('Avaliações respondidas: 50');
        $this->command->info('Links pendentes: 10');
    }
}
