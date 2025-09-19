<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class LoginController extends Controller
{
    // Remover middleware do constructor pois vamos usar nas rotas

    /**
     * Mostrar formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processar login
     */
    public function login(Request $request)
    {
        $request->validate([
            'aEmail' => 'required|email',
            'aSenha' => 'required|string|min:6'
        ], [
            'aEmail.required' => 'O campo e-mail é obrigatório.',
            'aEmail.email' => 'Digite um e-mail válido.',
            'aSenha.required' => 'O campo senha é obrigatório.',
            'aSenha.min' => 'A senha deve ter no mínimo 6 caracteres.'
        ]);

        $usuario = Usuario::where('aEmail', $request->aEmail)
                          ->where('nStatus', 0) // Apenas usuários ativos
                          ->first();

        if ($usuario && Hash::check($request->aSenha, $usuario->aSenha)) {
            Auth::login($usuario, $request->filled('remember'));

            // Regenerar sessão para segurança
            $request->session()->regenerate();

            // Redirecionar baseado no role
            return $this->redirectBasedOnRole($usuario);
        }

        return back()->withErrors([
            'aEmail' => 'Credenciais inválidas ou usuário inativo.',
        ])->withInput($request->only('aEmail'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('sucesso', 'Logout realizado com sucesso!');
    }

    /**
     * Redirecionar baseado no role do usuário
     */
    private function redirectBasedOnRole(Usuario $usuario)
    {
        switch ($usuario->aRole) {
            case 'admin':
                return redirect()->route('admin.dashboard')
                               ->with('sucesso', "Bem-vindo, {$usuario->aNome}! (Administrador)");
            
            case 'suporte':
                return redirect()->route('admin.dashboard')
                               ->with('sucesso', "Bem-vindo, {$usuario->aNome}! (Suporte)");
            
            case 'atendente':
                return redirect()->route('admin.index')
                               ->with('sucesso', "Bem-vindo, {$usuario->aNome}! (Atendente)");
            
            default:
                return redirect()->route('admin.index')
                               ->with('sucesso', "Bem-vindo, {$usuario->aNome}!");
        }
    }
}