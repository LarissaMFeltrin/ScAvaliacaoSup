<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdministrativoController extends Controller
{
    /**
     * Dashboard principal
     */
    public function dashboard(Request $request)
    {
        $idEmpresa = $request->get('nIdEmpresa');
        $idAtendente = $request->get('nIdAtendente');
        $dataInicio = $request->get('dDataInicio');
        $dataFim = $request->get('dDataFim');

        // Query base para avaliações completadas
        $queryAvaliacoes = Avaliacao::with(['empresa', 'atendente', 'usuarioGerador'])
                                   ->whereNotNull('nNotaAtendimento');

        // Query base para todas as avaliações (geradas)
        $queryTodasAvaliacoes = Avaliacao::with(['empresa', 'atendente', 'usuarioGerador']);

        // Aplicar filtros para avaliações completadas
        if ($idEmpresa) {
            $queryAvaliacoes->where('nIdEmpresa', $idEmpresa);
        }
        
        if ($idAtendente) {
            $queryAvaliacoes->where('nIdAtendente', $idAtendente);
        }
        
        if ($dataInicio) {
            $queryAvaliacoes->whereDate('dAvaliadoEm', '>=', $dataInicio);
        }
        
        if ($dataFim) {
            $queryAvaliacoes->whereDate('dAvaliadoEm', '<=', $dataFim);
        }

        // Aplicar filtros para todas as avaliações (geradas)
        if ($idEmpresa) {
            $queryTodasAvaliacoes->where('nIdEmpresa', $idEmpresa);
        }
        
        if ($idAtendente) {
            $queryTodasAvaliacoes->where('nIdAtendente', $idAtendente);
        }
        
        if ($dataInicio) {
            $queryTodasAvaliacoes->whereDate('dCriadoEm', '>=', $dataInicio);
        }
        
        if ($dataFim) {
            $queryTodasAvaliacoes->whereDate('dCriadoEm', '<=', $dataFim);
        }

        // Estatísticas
        $totalAvaliacoes = $queryAvaliacoes->count();
        $totalLinksGerados = $queryTodasAvaliacoes->count();
        $notaMedia = $queryAvaliacoes->avg('nNotaAtendimento');
        
        // Calcular taxa de conversão
        $taxaConversao = $totalLinksGerados > 0 ? ($totalAvaliacoes / $totalLinksGerados) * 100 : 0;
        
        // Distribuição por nota
        $distribuicaoNotas = $queryAvaliacoes
            ->select('nNotaAtendimento', DB::raw('count(*) as total'))
            ->groupBy('nNotaAtendimento')
            ->orderBy('nNotaAtendimento')
            ->get()
            ->pluck('total', 'nNotaAtendimento')
            ->toArray();

        // Preencher notas que não foram avaliadas
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($distribuicaoNotas[$i])) {
                $distribuicaoNotas[$i] = 0;
            }
        }
        ksort($distribuicaoNotas);

        // Avaliações recentes - criar nova query para evitar conflitos
        $avaliacoesRecentes = Avaliacao::with(['empresa', 'atendente'])
            ->whereNotNull('nNotaAtendimento')
            ->when($idEmpresa, function($query) use ($idEmpresa) {
                return $query->where('nIdEmpresa', $idEmpresa);
            })
            ->when($idAtendente, function($query) use ($idAtendente) {
                return $query->where('nIdAtendente', $idAtendente);
            })
            ->when($dataInicio, function($query) use ($dataInicio) {
                return $query->whereDate('dAvaliadoEm', '>=', $dataInicio);
            })
            ->when($dataFim, function($query) use ($dataFim) {
                return $query->whereDate('dAvaliadoEm', '<=', $dataFim);
            })
            ->orderBy('dAvaliadoEm', 'desc')
            ->limit(10)
            ->get();

        // Dados para filtros
        $empresas = Empresa::ativas()->get(['ID', 'aNome']);
        $atendentes = [];
        
        // Todos os usuários ativos podem atender qualquer empresa
        $atendentes = Usuario::ativos()->get(['ID', 'aNome']);

        return view('administrativo.dashboard', compact(
            'totalAvaliacoes',
            'totalLinksGerados',
            'taxaConversao',
            'notaMedia',
            'distribuicaoNotas',
            'avaliacoesRecentes',
            'empresas',
            'atendentes',
            'idEmpresa',
            'idAtendente',
            'dataInicio',
            'dataFim'
        ));
    }

    /**
     * Relatório detalhado
     */
    public function relatorio(Request $request)
    {
        $idEmpresa = $request->get('nIdEmpresa');
        $idAtendente = $request->get('nIdAtendente');
        $dataInicio = $request->get('dDataInicio');
        $dataFim = $request->get('dDataFim');

        $queryAvaliacoes = Avaliacao::with(['empresa', 'atendente', 'usuarioGerador'])
                                   ->whereNotNull('nNotaAtendimento');

        // Query para todas as avaliações (geradas)
        $queryTodasAvaliacoes = Avaliacao::with(['empresa', 'atendente', 'usuarioGerador']);

        // Aplicar mesmos filtros para avaliações completadas
        if ($idEmpresa) {
            $queryAvaliacoes->where('nIdEmpresa', $idEmpresa);
        }
        
        if ($idAtendente) {
            $queryAvaliacoes->where('nIdAtendente', $idAtendente);
        }
        
        if ($dataInicio) {
            $queryAvaliacoes->whereDate('dAvaliadoEm', '>=', $dataInicio);
        }
        
        if ($dataFim) {
            $queryAvaliacoes->whereDate('dAvaliadoEm', '<=', $dataFim);
        }

        // Aplicar mesmos filtros para todas as avaliações (geradas)
        if ($idEmpresa) {
            $queryTodasAvaliacoes->where('nIdEmpresa', $idEmpresa);
        }
        
        if ($idAtendente) {
            $queryTodasAvaliacoes->where('nIdAtendente', $idAtendente);
        }
        
        if ($dataInicio) {
            $queryTodasAvaliacoes->whereDate('dCriadoEm', '>=', $dataInicio);
        }
        
        if ($dataFim) {
            $queryTodasAvaliacoes->whereDate('dCriadoEm', '<=', $dataFim);
        }

        $avaliacoes = $queryAvaliacoes
            ->orderBy('dAvaliadoEm', 'desc')
            ->paginate(20);

        // Calcular estatísticas para o relatório
        $totalAvaliacoes = $queryAvaliacoes->count();
        $totalLinksGerados = $queryTodasAvaliacoes->count();
        $taxaConversao = $totalLinksGerados > 0 ? ($totalAvaliacoes / $totalLinksGerados) * 100 : 0;

        $empresas = Empresa::ativas()->get(['ID', 'aNome']);
        $atendentes = [];
        
        // Todos os usuários ativos podem atender qualquer empresa
        $atendentes = Usuario::ativos()->get(['ID', 'aNome']);

        return view('administrativo.relatorio', compact(
            'avaliacoes',
            'totalAvaliacoes',
            'totalLinksGerados',
            'taxaConversao',
            'empresas',
            'atendentes',
            'idEmpresa',
            'idAtendente',
            'dataInicio',
            'dataFim'
        ));
    }

    /**
     * API para buscar atendentes por empresa
     */
    public function obterAtendentes($idEmpresa)
    {
        // Retornar todos os usuários ativos (qualquer um pode atender qualquer empresa)
        $atendentes = Usuario::ativos()->get(['ID', 'aNome']);
        
        return response()->json($atendentes);
    }

    /**
     * API para buscar empresas por nome
     */
    public function buscarEmpresas(Request $request)
    {
        $termo = $request->get('q', '');
        $limit = $request->get('limit', 20);
        
        $query = Empresa::ativas();
        
        if (!empty($termo)) {
            $query->where('aNome', 'LIKE', "%{$termo}%");
        }
        
        $empresas = $query->orderBy('aNome')
            ->limit($limit)
            ->get(['ID', 'aNome']);
        
        return response()->json($empresas);
    }

    /**
     * API para buscar atendentes por nome
     */
    public function buscarAtendentes(Request $request)
    {
        $termo = $request->get('q', '');
        $limit = $request->get('limit', 20);
        
        $query = Usuario::ativos();
        
        if (!empty($termo)) {
            $query->where('aNome', 'LIKE', "%{$termo}%");
        }
        
        $atendentes = $query->orderBy('aNome')
            ->limit($limit)
            ->get(['ID', 'aNome']);
        
        return response()->json($atendentes);
    }

    /**
     * Gerar PDF de avaliações
     */
    public function pdfAvaliacoes(Request $request)
    {
        $idEmpresa = $request->get('nIdEmpresa');
        $idAtendente = $request->get('nIdAtendente');
        $dataInicio = $request->get('dDataInicio');
        $dataFim = $request->get('dDataFim');

        $queryAvaliacoes = Avaliacao::with(['empresa', 'atendente', 'usuarioGerador'])
                                   ->whereNotNull('nNotaAtendimento');

        // Aplicar mesmos filtros para avaliações completadas
        if ($idEmpresa) {
            $queryAvaliacoes->where('nIdEmpresa', $idEmpresa);
        }
        
        if ($idAtendente) {
            $queryAvaliacoes->where('nIdAtendente', $idAtendente);
        }
        
        if ($dataInicio) {
            $queryAvaliacoes->whereDate('dAvaliadoEm', '>=', $dataInicio);
        }
        
        if ($dataFim) {
            $queryAvaliacoes->whereDate('dAvaliadoEm', '<=', $dataFim);
        }

        $avaliacoes = $queryAvaliacoes->orderBy('dAvaliadoEm', 'desc')->get();

        // Calcular estatísticas
        $totalAvaliacoes = $avaliacoes->count();
        $notaMedia = $avaliacoes->avg('nNotaAtendimento');
        
        // Dados para filtros
        $empresa = $idEmpresa ? Empresa::find($idEmpresa) : null;
        $atendente = $idAtendente ? Usuario::find($idAtendente) : null;

        $pdf = Pdf::loadView('pdf.avaliacoes', compact(
            'avaliacoes',
            'totalAvaliacoes',
            'notaMedia',
            'empresa',
            'atendente',
            'dataInicio',
            'dataFim'
        ));

        $pdf->setPaper('A4', 'landscape');
        
        $nomeArquivo = 'relatorio_avaliacoes_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($nomeArquivo);
    }

    /**
     * Gerar PDF de produtividade
     */
    public function pdfProdutividade(Request $request)
    {
        $idEmpresa = $request->get('nIdEmpresa');
        $idAtendente = $request->get('nIdAtendente');
        $dataInicio = $request->get('dDataInicio');
        $dataFim = $request->get('dDataFim');

        // Query base para todas as avaliações
        $queryTodasAvaliacoes = Avaliacao::with(['empresa', 'atendente', 'usuarioGerador']);

        // Aplicar filtros
        if ($idEmpresa) {
            $queryTodasAvaliacoes->where('nIdEmpresa', $idEmpresa);
        }
        
        if ($idAtendente) {
            $queryTodasAvaliacoes->where('nIdAtendente', $idAtendente);
        }
        
        if ($dataInicio) {
            $queryTodasAvaliacoes->whereDate('dCriadoEm', '>=', $dataInicio);
        }
        
        if ($dataFim) {
            $queryTodasAvaliacoes->whereDate('dCriadoEm', '<=', $dataFim);
        }

        // Buscar produtividade por usuário
        $produtividadeUsuarios = $queryTodasAvaliacoes
            ->select('nIdUsuarioGerador', DB::raw('count(*) as total_links_gerados'))
            ->groupBy('nIdUsuarioGerador')
            ->get();

        // Buscar avaliações completadas por usuário
        $avaliacoesCompletadas = Avaliacao::with(['usuarioGerador'])
            ->whereNotNull('nNotaAtendimento')
            ->when($idEmpresa, function($query) use ($idEmpresa) {
                return $query->where('nIdEmpresa', $idEmpresa);
            })
            ->when($idAtendente, function($query) use ($idAtendente) {
                return $query->where('nIdAtendente', $idAtendente);
            })
            ->when($dataInicio, function($query) use ($dataInicio) {
                return $query->whereDate('dAvaliadoEm', '>=', $dataInicio);
            })
            ->when($dataFim, function($query) use ($dataFim) {
                return $query->whereDate('dAvaliadoEm', '<=', $dataFim);
            })
            ->select('nIdUsuarioGerador', DB::raw('count(*) as total_avaliacoes'))
            ->groupBy('nIdUsuarioGerador')
            ->get()
            ->keyBy('nIdUsuarioGerador');

        // Combinar dados
        $relatorioProdutividade = [];
        foreach ($produtividadeUsuarios as $item) {
            $usuario = Usuario::find($item->nIdUsuarioGerador);
            $avaliacoes = $avaliacoesCompletadas->get($item->nIdUsuarioGerador);
            
            // Se não encontrar o usuário, usar um nome padrão baseado no ID
            $nomeUsuario = 'Usuário não encontrado';
            if ($usuario) {
                $nomeUsuario = $usuario->aNome;
            } elseif ($item->nIdUsuarioGerador == 1) {
                $nomeUsuario = 'Sistema/Admin';
            } else {
                $nomeUsuario = 'Usuário ID: ' . $item->nIdUsuarioGerador;
            }
            
            $relatorioProdutividade[] = [
                'usuario' => $nomeUsuario,
                'total_links_gerados' => $item->total_links_gerados,
                'total_avaliacoes' => $avaliacoes ? $avaliacoes->total_avaliacoes : 0,
                'taxa_conversao' => $item->total_links_gerados > 0 ? 
                    round(($avaliacoes ? $avaliacoes->total_avaliacoes : 0) / $item->total_links_gerados * 100, 1) : 0
            ];
        }

        // Ordenar por total de links gerados
        usort($relatorioProdutividade, function($a, $b) {
            return $b['total_links_gerados'] <=> $a['total_links_gerados'];
        });

        // Dados para filtros
        $empresa = $idEmpresa ? Empresa::find($idEmpresa) : null;
        $atendente = $idAtendente ? Usuario::find($idAtendente) : null;

        $pdf = Pdf::loadView('pdf.produtividade', compact(
            'relatorioProdutividade',
            'empresa',
            'atendente',
            'dataInicio',
            'dataFim'
        ));

        $pdf->setPaper('A4', 'landscape');
        
        $nomeArquivo = 'relatorio_produtividade_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($nomeArquivo);
    }

}
