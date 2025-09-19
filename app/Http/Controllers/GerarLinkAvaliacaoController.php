<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GerarLinkAvaliacaoController extends Controller
{
    /**
     * Mostrar página de gerar links
     */
    public function index()
    {
        return view('gerar-link.index');
    }

    /**
     * Gerar link de avaliação
     */
    public function gerar(Request $request): JsonResponse
    {
        \Log::info('Requisição de gerar link recebida', $request->all());
        
        $request->validate([
            'nIdEmpresa' => 'required|exists:empresas,ID',
            'nIdAtendente' => 'required|exists:usuarios,ID'
        ]);

        // Verificar se o usuário é um atendente ativo
        $atendente = Usuario::where('ID', $request->nIdAtendente)
                            ->where('nStatus', 0) // Ativo
                            ->first();

        if (!$atendente) {
            return response()->json([
                'erro' => 'Usuário não encontrado ou inativo'
            ], 422);
        }

        // Criar nova avaliação
        $avaliacao = Avaliacao::create([
            'nIdEmpresa' => $request->nIdEmpresa,
            'nIdAtendente' => $request->nIdAtendente
        ]);

        $link = url("/avaliacao/{$avaliacao->aToken}");
        
        $response = [
            'sucesso' => true,
            'link' => $link,
            'token' => $avaliacao->aToken,
            'empresa' => $avaliacao->empresa->aNome,
            'atendente' => $atendente->aNome
        ];
        
        \Log::info('Resposta sendo enviada', $response);

        return response()->json($response);
    }

    /**
     * Listar empresas para seleção
     */
    public function empresas(): JsonResponse
    {
        $empresas = Empresa::ativas()
                           ->get(['ID', 'aNome']);

        return response()->json($empresas);
    }

    /**
     * Listar usuários que podem atender (todos os usuários ativos)
     */
    public function atendentes($idEmpresa): JsonResponse
    {
        $atendentes = Usuario::ativos()
                               ->get(['ID', 'aNome']);

        return response()->json($atendentes);
    }

}
