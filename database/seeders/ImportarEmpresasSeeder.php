<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;

class ImportarEmpresasSeeder extends Seeder
{
    public function run(): void
    {
        echo "📂 Importando TODAS as 69 empresas do arquivo SQL...\n";
        
        // Ler arquivo SQL
        $sqlFile = database_path('sql/BaseEmpresa.sql');
        
        if (!file_exists($sqlFile)) {
            echo "❌ Arquivo BaseEmpresa.sql não encontrado!\n";
            return;
        }
        
        $sqlContent = file_get_contents($sqlFile);
        
        // Extrair todas as linhas de INSERT que começam com (
        preg_match_all('/^\((.+)\),?$/m', $sqlContent, $matches);
        
        $empresasImportadas = 0;
        $empresasAtivas = 0;
        
        echo "🔍 Encontradas " . count($matches[1]) . " linhas de dados\n";
        
        foreach ($matches[1] as $linha) {
            // Dividir a linha pelos campos (considerando aspas)
            $campos = $this->parsearLinhaSql($linha);
            
            if (count($campos) < 10) {
                continue; // Pular linhas malformadas
            }
            
            // Mapear campos conforme a estrutura da tabela BaseEmpresa
            $id = $this->limparCampo($campos[0]);
            $razaoSocial = $this->limparCampo($campos[1]);
            $nomeFantasia = $this->limparCampo($campos[2]);
            $endereco = $this->limparCampo($campos[3]);
            $numero = $this->limparCampo($campos[4]);
            $complemento = $this->limparCampo($campos[5]);
            $fone1 = $this->limparCampo($campos[6]);
            $fone2 = $this->limparCampo($campos[7]);
            $fone3 = $this->limparCampo($campos[8]);
            $email = isset($campos[12]) ? $this->limparCampo($campos[12]) : '';
            $status = isset($campos[39]) ? (int)$this->limparCampo($campos[39]) : 0;
            
            // Determinar nome (priorizar nome fantasia)
            $nome = !empty($nomeFantasia) ? $nomeFantasia : $razaoSocial;
            
            // Pular se nome vazio ou inválido
            if (empty($nome) || strlen($nome) < 2) {
                continue;
            }
            
            // Montar telefone
            $telefone = '';
            if (!empty($fone1)) $telefone = $fone1;
            elseif (!empty($fone2)) $telefone = $fone2;
            elseif (!empty($fone3)) $telefone = $fone3;
            
            // Montar endereço completo
            $enderecoCompleto = trim($endereco);
            if (!empty($numero)) {
                $enderecoCompleto .= ', ' . $numero;
            }
            if (!empty($complemento)) {
                $enderecoCompleto .= ' - ' . $complemento;
            }
            
            // Validar email
            $emailValido = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
            
            try {
                $empresa = Empresa::create([
                    'aNome' => $nome,
                    'aEmail' => $emailValido,
                    'aTelefone' => !empty($telefone) ? $telefone : null,
                    'aEndereco' => !empty($enderecoCompleto) ? $enderecoCompleto : null,
                    'nStatus' => ($status == 0) ? 0 : 99
                ]);
                
                $empresasImportadas++;
                if ($status == 0) {
                    $empresasAtivas++;
                }
                
                $statusTexto = ($status == 0) ? '✅ Ativa' : '⚠️  Inativa';
                echo "   {$statusTexto} - {$nome}\n";
                
            } catch (\Exception $e) {
                echo "   ❌ Erro ao importar '{$nome}': " . $e->getMessage() . "\n";
            }
        }
        
        echo "\n📊 RESUMO FINAL:\n";
        echo "   📈 Total importadas: {$empresasImportadas}\n";
        echo "   ✅ Empresas ativas: {$empresasAtivas}\n";
        echo "   ❌ Empresas inativas: " . ($empresasImportadas - $empresasAtivas) . "\n";
        echo "   📋 Total no banco: " . Empresa::count() . "\n";
        echo "\n🎉 Importação de TODAS as empresas concluída!\n";
    }
    
    private function parsearLinhaSql($linha): array
    {
        $campos = [];
        $campo = '';
        $dentroAspas = false;
        $i = 0;
        
        while ($i < strlen($linha)) {
            $char = $linha[$i];
            
            if ($char == "'" && ($i == 0 || $linha[$i-1] != '\\')) {
                $dentroAspas = !$dentroAspas;
            } elseif ($char == ',' && !$dentroAspas) {
                $campos[] = trim($campo);
                $campo = '';
                $i++;
                continue;
            } else {
                $campo .= $char;
            }
            $i++;
        }
        
        // Adicionar último campo
        if (!empty($campo)) {
            $campos[] = trim($campo);
        }
        
        return $campos;
    }
    
    private function limparCampo($campo): string
    {
        $campo = trim($campo);
        
        // Remover aspas simples
        if (substr($campo, 0, 1) == "'" && substr($campo, -1) == "'") {
            $campo = substr($campo, 1, -1);
        }
        
        // Converter NULL
        if (strtoupper($campo) == 'NULL') {
            return '';
        }
        
        return $campo;
    }
}