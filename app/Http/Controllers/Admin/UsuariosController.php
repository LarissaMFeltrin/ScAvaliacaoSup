<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    /**
     * Listar todos os usuários
     */
    public function index(Request $request)
    {
        $busca = $request->get('busca');
        $role = $request->get('role');
        
        $query = Usuario::query();
        
        if ($busca) {
            $query->where(function($q) use ($busca) {
                $q->where('aNome', 'like', "%{$busca}%")
                  ->orWhere('aEmail', 'like', "%{$busca}%");
            });
        }
        
        if ($role) {
            $query->where('aRole', $role);
        }
        
        $usuarios = $query->orderBy('aNome')->paginate(20);
        
        return view('admin.usuarios.index', compact('usuarios', 'busca', 'role'));
    }
    
    /**
     * Mostrar formulário de criação
     */
    public function create()
    {
        $empresas = Empresa::ativas()->orderBy('aNome')->get();
        return view('admin.usuarios.create', compact('empresas'));
    }
    
    /**
     * Salvar novo usuário
     */
    public function store(Request $request)
    {
        $request->validate([
            'aNome' => 'required|string|max:255',
            'aEmail' => 'required|email|max:255|unique:usuarios,aEmail',
            'aSenha' => 'required|string|min:6|confirmed',
            'aRole' => 'required|in:admin,suporte,atendente',
            'nIdEmpresa' => 'nullable|exists:empresas,ID',
            'nStatus' => 'required|in:0,99'
        ], [
            'aNome.required' => 'O nome é obrigatório.',
            'aNome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'aEmail.required' => 'O e-mail é obrigatório.',
            'aEmail.email' => 'Digite um e-mail válido.',
            'aEmail.unique' => 'Este e-mail já está em uso.',
            'aSenha.required' => 'A senha é obrigatória.',
            'aSenha.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'aSenha.confirmed' => 'A confirmação da senha não confere.',
            'aRole.required' => 'Selecione o perfil do usuário.',
            'aRole.in' => 'Perfil inválido.',
            'nIdEmpresa.exists' => 'Empresa não encontrada.',
            'nStatus.required' => 'Selecione o status.',
            'nStatus.in' => 'Status inválido.'
        ]);
        
        $usuario = Usuario::create($request->all());
        
        return redirect()->route('admin.usuarios.index')
                        ->with('sucesso', "Usuário '{$usuario->aNome}' criado com sucesso!");
    }
    
    /**
     * Mostrar formulário de edição
     */
    public function edit(Usuario $usuario)
    {
        $empresas = Empresa::ativas()->orderBy('aNome')->get();
        return view('admin.usuarios.edit', compact('usuario', 'empresas'));
    }
    
    /**
     * Atualizar usuário
     */
    public function update(Request $request, Usuario $usuario)
    {
        $rules = [
            'aNome' => 'required|string|max:255',
            'aEmail' => 'required|email|max:255|unique:usuarios,aEmail,' . $usuario->ID . ',ID',
            'aRole' => 'required|in:admin,suporte,atendente',
            'nIdEmpresa' => 'nullable|exists:empresas,ID',
            'nStatus' => 'required|in:0,99'
        ];
        
        // Só validar senha se foi preenchida
        if ($request->filled('aSenha')) {
            $rules['aSenha'] = 'string|min:6|confirmed';
        }
        
        $request->validate($rules, [
            'aNome.required' => 'O nome é obrigatório.',
            'aNome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'aEmail.required' => 'O e-mail é obrigatório.',
            'aEmail.email' => 'Digite um e-mail válido.',
            'aEmail.unique' => 'Este e-mail já está em uso.',
            'aSenha.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'aSenha.confirmed' => 'A confirmação da senha não confere.',
            'aRole.required' => 'Selecione o perfil do usuário.',
            'aRole.in' => 'Perfil inválido.',
            'nIdEmpresa.exists' => 'Empresa não encontrada.',
            'nStatus.required' => 'Selecione o status.',
            'nStatus.in' => 'Status inválido.'
        ]);
        
        $dados = $request->except(['aSenha', 'aSenha_confirmation']);
        
        // Só atualizar senha se foi preenchida
        if ($request->filled('aSenha')) {
            $dados['aSenha'] = $request->aSenha;
        }
        
        $usuario->update($dados);
        
        return redirect()->route('admin.usuarios.index')
                        ->with('sucesso', "Usuário '{$usuario->aNome}' atualizado com sucesso!");
    }
    
    /**
     * Excluir usuário
     */
    public function destroy(Usuario $usuario)
    {
        // Não permitir que admin exclua a si mesmo
        if ($usuario->ID === auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                           ->with('erro', 'Você não pode excluir sua própria conta.');
        }
        
        $nome = $usuario->aNome;
        $usuario->delete();
        
        return redirect()->route('admin.usuarios.index')
                        ->with('sucesso', "Usuário '{$nome}' excluído com sucesso!");
    }
}