<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Atendente;
use Illuminate\Http\Request;

class AtendentesController extends Controller
{
    /**
     * Listar todos os atendentes
     */
    public function index(Request $request)
    {
        $busca = $request->get('busca');
        
        $query = Atendente::query();
        
        if ($busca) {
            $query->where(function($q) use ($busca) {
                $q->where('aNome', 'like', "%{$busca}%")
                  ->orWhere('aEmail', 'like', "%{$busca}%");
            });
        }
        
        $atendentes = $query->orderBy('aNome')->paginate(20);
        
        return view('admin.atendentes.index', compact('atendentes', 'busca'));
    }
    
    /**
     * Mostrar formulário de criação
     */
    public function create()
    {
        return view('admin.atendentes.create');
    }
    
    /**
     * Salvar novo atendente
     */
    public function store(Request $request)
    {
        $request->validate([
            'aNome' => 'required|string|max:255',
            'aEmail' => 'nullable|email|max:255|unique:atendentes,aEmail',
            'nStatus' => 'required|in:0,99'
        ], [
            'aNome.required' => 'O nome é obrigatório.',
            'aNome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'aEmail.email' => 'Digite um e-mail válido.',
            'aEmail.unique' => 'Este e-mail já está em uso.',
            'nStatus.required' => 'Selecione o status.',
            'nStatus.in' => 'Status inválido.'
        ]);
        
        $atendente = Atendente::create($request->all());
        
        return redirect()->route('admin.atendentes.index')
                        ->with('sucesso', "Atendente '{$atendente->aNome}' criado com sucesso!");
    }
    
    /**
     * Mostrar formulário de edição
     */
    public function edit(Atendente $atendente)
    {
        return view('admin.atendentes.edit', compact('atendente'));
    }
    
    /**
     * Atualizar atendente
     */
    public function update(Request $request, Atendente $atendente)
    {
        $request->validate([
            'aNome' => 'required|string|max:255',
            'aEmail' => 'nullable|email|max:255|unique:atendentes,aEmail,' . $atendente->ID . ',ID',
            'nStatus' => 'required|in:0,99'
        ], [
            'aNome.required' => 'O nome é obrigatório.',
            'aNome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'aEmail.email' => 'Digite um e-mail válido.',
            'aEmail.unique' => 'Este e-mail já está em uso.',
            'nStatus.required' => 'Selecione o status.',
            'nStatus.in' => 'Status inválido.'
        ]);
        
        $atendente->update($request->all());
        
        return redirect()->route('admin.atendentes.index')
                        ->with('sucesso', "Atendente '{$atendente->aNome}' atualizado com sucesso!");
    }
    
    /**
     * Excluir atendente
     */
    public function destroy(Atendente $atendente)
    {
        $nome = $atendente->aNome;
        
        // Verificar se há avaliações vinculadas
        if ($atendente->avaliacoes()->count() > 0) {
            return redirect()->route('admin.atendentes.index')
                           ->with('erro', "Não é possível excluir '{$nome}' pois há avaliações vinculadas.");
        }
        
        $atendente->delete();
        
        return redirect()->route('admin.atendentes.index')
                        ->with('sucesso', "Atendente '{$nome}' excluído com sucesso!");
    }
}