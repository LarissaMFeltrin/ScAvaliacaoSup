<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GerarLinkAvaliacaoController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AdministrativoController;
use App\Http\Controllers\Auth\LoginController;

// Página inicial (pública)
Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    
    // Rotas administrativas (requer login)
    Route::prefix('admin')->group(function () {
        
        // Rotas para atendentes (podem gerar links)
        Route::middleware(['role:admin,suporte,atendente'])->group(function () {
            Route::get('/', [GerarLinkAvaliacaoController::class, 'index'])->name('admin.index');
            Route::get('/empresas', [GerarLinkAvaliacaoController::class, 'empresas'])->name('admin.empresas');
            Route::get('/empresas/{idEmpresa}/atendentes', [GerarLinkAvaliacaoController::class, 'atendentes'])->name('admin.atendentes');
            Route::post('/gerar-link', [GerarLinkAvaliacaoController::class, 'gerar'])->name('admin.gerar-link');
        });
        
        // Rotas para suporte e admin (dashboard e relatórios)
        Route::middleware(['role:admin,suporte'])->group(function () {
            Route::get('/dashboard', [AdministrativoController::class, 'dashboard'])->name('admin.dashboard');
            Route::get('/relatorio', [AdministrativoController::class, 'relatorio'])->name('admin.relatorio');
            Route::get('/api/empresas/{idEmpresa}/atendentes', [AdministrativoController::class, 'obterAtendentes'])->name('admin.api.atendentes');
            Route::get('/api/empresas/buscar', [AdministrativoController::class, 'buscarEmpresas'])->name('admin.api.empresas.buscar');
            Route::get('/api/atendentes/buscar', [AdministrativoController::class, 'buscarAtendentes'])->name('admin.api.atendentes.buscar');
        });
        
        // Rotas apenas para admin (gerenciar usuários)
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('/usuarios', \App\Http\Controllers\Admin\UsuariosController::class)->names([
                'index' => 'admin.usuarios.index',
                'create' => 'admin.usuarios.create',
                'store' => 'admin.usuarios.store',
                'edit' => 'admin.usuarios.edit',
                'update' => 'admin.usuarios.update',
                'destroy' => 'admin.usuarios.destroy'
            ]);
        });
    });
});

// Rotas públicas para avaliação (não requerem login)
Route::get('/avaliacao/{token}', [AvaliacaoController::class, 'mostrar'])->name('avaliacao.mostrar');
Route::post('/avaliacao/{token}', [AvaliacaoController::class, 'enviar'])->name('avaliacao.enviar');
