<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciar Atendentes - SCORDON</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- CSS Global Scordon -->
    <link href="{{ asset('css/scordon.css') }}" rel="stylesheet">
    <!-- Meta CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'scordon': {
                            50: '#FFFBEB',
                            100: '#FEF3C7',
                            200: '#FDE68A',
                            300: '#FCD34D',
                            400: '#FBBF24',
                            500: '#F59E0B',
                            600: '#D97706',
                            700: '#B45309',
                            800: '#92400E',
                            900: '#78350F',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-gray-50 to-scordon-50 min-h-screen">
    <!-- Header com Gradient Amarelo -->
    <header class="gradient-bg shadow-lg">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('imagens/cordonpreto.png') }}" alt="Scordon" class="h-12 w-12">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">SCORDON</h1>
                        <p class="text-sm opacity-90">Sistema de Avaliação</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-6 text-white">
                    <a href="{{ url('/') }}" class="hover:text-scordon-100 transition-colors">
                        <i class="fas fa-home mr-2"></i>Início
                    </a>
                    <a href="{{ route('admin.index') }}" class="hover:text-scordon-100 transition-colors">
                        <i class="fas fa-link mr-2"></i>Gerar Links
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-scordon-100 transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.relatorio') }}" class="hover:text-scordon-100 transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>Relatórios
                    </a>
                    <a href="{{ route('admin.atendentes.index') }}" class="hover:text-scordon-100 transition-colors font-semibold">
                        <i class="fas fa-users mr-2"></i>Atendentes
                    </a>
                    
                    <!-- Informações do Usuário -->
                    <div class="flex items-center space-x-4 border-l border-white/20 pl-6">
                        <div class="text-right">
                            <div class="text-sm font-semibold">{{ auth()->user()->aNome }}</div>
                            <div class="text-xs opacity-75">{{ auth()->user()->nome_role }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-red-300 transition-colors" title="Logout">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <section class="py-12">
        <div class="container mx-auto px-6">
            
            <!-- Header da Página -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-users text-scordon-500 mr-3"></i>
                    Gerenciar Atendentes
                </h1>
                <p class="text-xl text-gray-600 mb-6">Cadastre e gerencie os atendentes das empresas</p>
                <a href="{{ route('admin.atendentes.create') }}" class="btn-scordon">
                    <i class="fas fa-plus mr-2"></i>
                    Novo Atendente
                </a>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-search text-scordon-500 mr-2"></i>
                    Buscar Atendentes
                </h3>
                <form method="GET" action="{{ route('admin.atendentes.index') }}" class="grid md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label for="busca" class="block text-sm font-semibold text-gray-700 mb-2">Buscar por nome ou e-mail</label>
                        <input type="text" name="busca" id="busca" class="w-full form-control-scordon" 
                               placeholder="Digite o nome ou e-mail do atendente..." value="{{ $busca }}">
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 btn-scordon">
                            <i class="fas fa-search mr-2"></i>
                            Buscar
                        </button>
                        <a href="{{ route('admin.atendentes.index') }}" class="flex-1 btn-outline-scordon text-center">
                            <i class="fas fa-times mr-2"></i>
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Resumo dos Resultados -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-list text-scordon-500 mr-2"></i>
                            Lista de Atendentes
                        </h3>
                        <p class="text-gray-600">{{ $atendentes->total() }} atendentes encontrados</p>
                    </div>
                    
                    <div class="flex space-x-4 mt-4 md:mt-0">
                        <a href="{{ route('admin.atendentes.create') }}" class="btn-verde">
                            <i class="fas fa-plus mr-2"></i>
                            Novo Atendente
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabela de Atendentes -->
            @if($atendentes->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                    <div class="overflow-x-auto">
                        <table class="w-full table-scordon">
                            <thead class="gradient-bg text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold">Nome</th>
                                    <th class="px-6 py-4 text-left font-semibold">E-mail</th>
                                    <th class="px-6 py-4 text-center font-semibold">Status</th>
                                    <th class="px-6 py-4 text-center font-semibold">Avaliações</th>
                                    <th class="px-6 py-4 text-center font-semibold">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($atendentes as $atendente)
                                    <tr class="hover:bg-scordon-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-800">
                                                {{ $atendente->aNome }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <i class="fas fa-user-tie mr-1"></i>
                                                Funcionário SCORDON
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($atendente->aEmail)
                                                <div class="text-gray-700">{{ $atendente->aEmail }}</div>
                                            @else
                                                <span class="text-gray-400 italic">Sem e-mail</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($atendente->isAtivo())
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Ativo
                                                </span>
                                            @else
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Inativo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-lg font-semibold text-gray-800">
                                                {{ $atendente->avaliacoes()->count() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('admin.atendentes.edit', $atendente) }}" 
                                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition-colors"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.atendentes.destroy', $atendente) }}" 
                                                      class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este atendente?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition-colors"
                                                            title="Excluir">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginação -->
                    @if($atendentes->hasPages())
                        <div class="bg-gray-50 px-6 py-4 border-t">
                            <div class="pagination-info mb-4">
                                <i class="fas fa-info-circle text-scordon-500 mr-2"></i>
                                Mostrando {{ $atendentes->firstItem() }} até {{ $atendentes->lastItem() }} de {{ $atendentes->total() }} atendentes
                            </div>
                            <div class="flex justify-center">
                                {{ $atendentes->withQueryString()->links('custom.pagination') }}
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <!-- Estado Vazio -->
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                    <div class="mb-6">
                        <i class="fas fa-users text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Nenhum atendente encontrado</h3>
                    <p class="text-gray-600 mb-8">
                        Comece cadastrando o primeiro atendente para uma empresa.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('admin.atendentes.create') }}" class="btn-scordon">
                            <i class="fas fa-plus mr-2"></i>
                            Cadastrar Primeiro Atendente
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn-verde">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Ver Dashboard
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Rodapé -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center space-x-4 mb-6 md:mb-0">
                    <img src="{{ asset('imagens/cordonamarelo.png') }}" alt="Scordon" class="h-10 w-10">
                    <div>
                        <div class="text-xl font-bold">Sistemas SCORDON</div>
                        <div class="text-scordon-300 text-sm">Desde 1993 • Mais de 30 anos de experiência</div>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <div class="flex flex-col space-y-2 text-sm">
                        <div>
                            <i class="fas fa-phone text-scordon-400 mr-2"></i>
                            <span>(44) 3028-4949</span>
                        </div>
                        <div>
                            <i class="fab fa-whatsapp text-scordon-400 mr-2"></i>
                            <span>(44) 98811-7771</span>
                        </div>
                        <div>
                            <i class="fas fa-envelope text-scordon-400 mr-2"></i>
                            <span>suporte@scordon.com.br</span>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-8 border-gray-700">
            <div class="text-center text-gray-400 text-sm">
                © {{ date('Y') }} Sistemas SCORDON. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/scordon.js') }}"></script>
    
    <script>
        // Mostrar notificações
        @if(session('sucesso'))
            mostrarNotificacao('{{ session('sucesso') }}', 'sucesso');
        @endif

        @if(session('erro'))
            mostrarNotificacao('{{ session('erro') }}', 'erro');
        @endif
    </script>
</body>
</html>
