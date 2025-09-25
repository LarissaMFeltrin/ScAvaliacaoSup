<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('erro', 'Você precisa estar logado para acessar esta página.');
        }

        $user = auth()->user();
        
        if (!$user->isAtivo()) {
            auth()->logout();
            return redirect()->route('login')->with('erro', 'Sua conta está inativa. Entre em contato com o administrador.');
        }

        if (empty($roles) || $user->hasAnyRole($roles)) {
            return $next($request);
        }

        // Usuário não tem permissão - redirecionar para página apropriada
        return redirect()->route('admin.redirect')->with('erro', 'Você não tem permissão para acessar esta página.');
    }
}