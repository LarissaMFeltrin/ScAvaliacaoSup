<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    /**
     * Mostrar formulário de avaliação
     */
    public function mostrar($token)
    {
        $avaliacao = Avaliacao::where('aToken', $token)
                              ->with(['empresa', 'atendente'])
                              ->firstOrFail();

        // Se já foi avaliado, mostrar página de agradecimento
        if ($avaliacao->foiAvaliada()) {
            return view('avaliacao.obrigado', compact('avaliacao'));
        }

        return view('avaliacao.formulario', compact('avaliacao'));
    }

    /**
     * Processar avaliação
     */
    public function enviar(Request $request, $token)
    {
        $avaliacao = Avaliacao::where('aToken', $token)->firstOrFail();

        // Verificar se já foi avaliado
        if ($avaliacao->foiAvaliada()) {
            return redirect()->route('avaliacao.mostrar', $token)
                           ->with('erro', 'Esta avaliação já foi respondida.');
        }

        $request->validate([
            'nNota' => 'required|integer|between:1,5',
            'aComentario' => 'nullable|string|max:1000',
            'aNomeCliente' => 'nullable|string|max:255',
            'aEmailCliente' => 'nullable|email|max:255'
        ]);

        $avaliacao->update([
            'nNota' => $request->nNota,
            'aComentario' => $request->aComentario,
            'aNomeCliente' => $request->aNomeCliente,
            'aEmailCliente' => $request->aEmailCliente,
            'dAvaliadoEm' => now()
        ]);

        return redirect()->route('avaliacao.mostrar', $token)
                       ->with('sucesso', 'Obrigado pela sua avaliação!');
    }
}
