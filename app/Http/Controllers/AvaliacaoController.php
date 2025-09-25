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
            if ($avaliacao->isComercial()) {
                return view('avaliacao-comercial.obrigado', compact('avaliacao'));
            }
            return view('avaliacao.obrigado', compact('avaliacao'));
        }

        // Mostrar formulário apropriado baseado no tipo
        if ($avaliacao->isComercial()) {
            return view('avaliacao-comercial.formulario', compact('avaliacao'));
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

        // Validação baseada no tipo de avaliação
        if ($avaliacao->isComercial()) {
            $request->validate([
                'nNotaAtendimento' => 'required|integer|between:1,5',
                'nAtendeuExpectativas' => 'required|integer|between:1,3',
                'aComentario' => 'nullable|string|max:1000',
                'aNomeCliente' => 'nullable|string|max:255',
                'aEmailCliente' => 'nullable|email|max:255'
            ]);

            $avaliacao->update([
                'nNotaAtendimento' => $request->nNotaAtendimento,
                'nAtendeuExpectativas' => $request->nAtendeuExpectativas,
                'aComentario' => $request->aComentario,
                'aNomeCliente' => $request->aNomeCliente,
                'aEmailCliente' => $request->aEmailCliente,
                'dAvaliadoEm' => now()
            ]);
        } else {
            $request->validate([
                'nNotaAtendimento' => 'required|integer|between:1,5',
                'aComentario' => 'nullable|string|max:1000',
                'aNomeCliente' => 'nullable|string|max:255',
                'aEmailCliente' => 'nullable|email|max:255'
            ]);

            $avaliacao->update([
                'nNotaAtendimento' => $request->nNotaAtendimento,
                'aComentario' => $request->aComentario,
                'aNomeCliente' => $request->aNomeCliente,
                'aEmailCliente' => $request->aEmailCliente,
                'dAvaliadoEm' => now()
            ]);
        }

        return redirect()->route('avaliacao.mostrar', $token)
                       ->with('sucesso', 'Obrigado pela sua avaliação!');
    }
}
