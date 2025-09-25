<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GerarLinkAvaliacaoComercialController extends Controller
{
    /**
     * Mostrar página de gerar links comerciais
     */
    public function index()
    {
        return view('gerar-link-comercial.index');
    }

    /**
     * Gerar link de avaliação comercial
     */
    public function gerar(Request $request): JsonResponse
    {
        \Log::info('Requisição de gerar link comercial recebida', $request->all());
        
        $request->validate([
            'nIdEmpresa' => 'required|exists:empresas,ID',
            'nIdAtendente' => 'required|exists:usuarios,ID'
        ]);

        // Verificar se o vendedor está ativo
        $vendedor = Usuario::where('ID', $request->nIdAtendente)
                           ->where('nStatus', 0) // Ativo
                           ->where('aRole', 'vendedor') // Deve ser vendedor
                           ->first();

        if (!$vendedor) {
            return response()->json([
                'erro' => 'Vendedor não encontrado ou inativo'
            ], 422);
        }

        // Criar nova avaliação comercial
        $avaliacao = Avaliacao::create([
            'aTipo' => 'comercial',
            'nIdEmpresa' => $request->nIdEmpresa,
            'nIdAtendente' => $request->nIdAtendente,
            'nIdUsuarioGerador' => auth()->id(),
            'dCriadoEm' => now()
        ]);

        $link = url("/avaliacao/{$avaliacao->aToken}");
        
        $response = [
            'sucesso' => true,
            'link' => $link,
            'token' => $avaliacao->aToken,
            'empresa' => $avaliacao->empresa->aNome,
            'vendedor' => $vendedor->aNome
        ];
        
        \Log::info('Resposta sendo enviada', $response);

        return response()->json($response);
    }


    /**
     * Listar vendedores ativos
     */
    public function vendedores($idEmpresa): JsonResponse
    {
        // Para o sistema comercial, todos os vendedores podem atender qualquer empresa
        $vendedores = Usuario::where('aRole', 'vendedor')
                             ->where('nStatus', 0)
                             ->get(['ID', 'aNome']);

        return response()->json($vendedores);
    }

}