<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Query base
        $queryAvaliacoes = Avaliacao::with(['empresa', 'atendente'])
                                   ->whereNotNull('nNota');

        // Filtros
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

        // Estatísticas
        $totalAvaliacoes = $queryAvaliacoes->count();
        $notaMedia = $queryAvaliacoes->avg('nNota');
        
        // Distribuição por nota
        $distribuicaoNotas = $queryAvaliacoes
            ->select('nNota', DB::raw('count(*) as total'))
            ->groupBy('nNota')
            ->orderBy('nNota')
            ->get()
            ->pluck('total', 'nNota')
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
            ->whereNotNull('nNota')
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

        $queryAvaliacoes = Avaliacao::with(['empresa', 'atendente'])
                                   ->whereNotNull('nNota');

        // Aplicar mesmos filtros
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

        $avaliacoes = $queryAvaliacoes
            ->orderBy('dAvaliadoEm', 'desc')
            ->paginate(20);

        $empresas = Empresa::ativas()->get(['ID', 'aNome']);
        $atendentes = [];
        
        // Todos os usuários ativos podem atender qualquer empresa
        $atendentes = Usuario::ativos()->get(['ID', 'aNome']);

        return view('administrativo.relatorio', compact(
            'avaliacoes',
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
}
