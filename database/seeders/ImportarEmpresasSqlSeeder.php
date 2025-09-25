<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportarEmpresasSqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando importação dos arquivos SQL...');

        // Importar clientes
        $this->importarClientes();
        
        // Importar compradores
        $this->importarCompradores();

        $this->command->info('Importação concluída com sucesso!');
    }

    private function importarClientes(): void
    {
        $this->command->info('Importando clientes...');
        
        $sqlFile = database_path('sql/CdDsk_cliente.sql');
        
        if (!File::exists($sqlFile)) {
            $this->command->error('Arquivo CdDsk_cliente.sql não encontrado!');
            return;
        }

        $sql = File::get($sqlFile);
        
        // Executar o SQL diretamente
        DB::unprepared($sql);
        
        // Agora migrar os dados da tabela CdDsk_cliente para empresas
        $clientes = DB::table('CdDsk_cliente')->get();
        
        foreach ($clientes as $cliente) {
            // Verificar se já existe uma empresa com esse nome
            $empresaExistente = Empresa::where('aNome', $cliente->Nome)->first();
            
            if (!$empresaExistente) {
                Empresa::create([
                    'aNome' => $cliente->Nome,
                    'aEmail' => $cliente->Email ?? null,
                    'aTelefone' => $cliente->Telefone ?? $cliente->Celular ?? null,
                    'aEndereco' => $this->montarEndereco($cliente),
                    'nStatus' => $cliente->Status == 1 ? 99 : 0, // Inverter lógica se necessário
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        $this->command->info('Clientes importados: ' . $clientes->count());
        
        // Remover a tabela temporária
        DB::statement('DROP TABLE IF EXISTS CdDsk_cliente');
    }

    private function importarCompradores(): void
    {
        $this->command->info('Importando compradores...');
        
        $sqlFile = database_path('sql/CdDsk_comprador.sql');
        
        if (!File::exists($sqlFile)) {
            $this->command->error('Arquivo CdDsk_comprador.sql não encontrado!');
            return;
        }

        $sql = File::get($sqlFile);
        
        // Executar o SQL diretamente
        DB::unprepared($sql);
        
        // Agora migrar os dados da tabela CdDsk_comprador para empresas
        $compradores = DB::table('CdDsk_comprador')->get();
        
        foreach ($compradores as $comprador) {
            // Verificar se já existe uma empresa com esse nome
            $empresaExistente = Empresa::where('aNome', $comprador->Nome)->first();
            
            if (!$empresaExistente) {
                Empresa::create([
                    'aNome' => $comprador->Nome,
                    'aEmail' => $comprador->Email ?? null,
                    'aTelefone' => $comprador->Telefone ?? $comprador->Celular ?? null,
                    'aEndereco' => $this->montarEndereco($comprador),
                    'nStatus' => 0, // Compradores ativos por padrão
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        $this->command->info('Compradores importados: ' . $compradores->count());
        
        // Remover a tabela temporária
        DB::statement('DROP TABLE IF EXISTS CdDsk_comprador');
    }

    private function montarEndereco($registro): ?string
    {
        $endereco = '';
        
        if (!empty($registro->Endereco)) {
            $endereco .= $registro->Endereco;
        }
        
        if (!empty($registro->Numero)) {
            $endereco .= ', ' . $registro->Numero;
        }
        
        if (!empty($registro->Bairro)) {
            $endereco .= ' - ' . $registro->Bairro;
        }
        
        if (!empty($registro->CEP)) {
            $endereco .= ' - CEP: ' . $registro->CEP;
        }
        
        if (!empty($registro->UF)) {
            $endereco .= ' - ' . $registro->UF;
        }
        
        return !empty($endereco) ? $endereco : null;
    }
}