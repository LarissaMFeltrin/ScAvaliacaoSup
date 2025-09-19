<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciar Usuários - SCORDON</title>
    
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
                    <a href="{{ route('admin.usuarios.index') }}" class="hover:text-scordon-100 transition-colors font-semibold">
                        <i class="fas fa-users-cog mr-2"></i>Usuários
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
                    <i class="fas fa-users-cog text-scordon-500 mr-3"></i>
                    Gerenciar Usuários
                </h1>
                <p class="text-xl text-gray-600 mb-6">Cadastre e gerencie usuários do sistema</p>
                <a href="{{ route('admin.usuarios.create') }}" class="btn-scordon">
                    <i class="fas fa-user-plus mr-2"></i>
                    Novo Usuário
                </a>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-filter text-scordon-500 mr-2"></i>
                    Filtros de Pesquisa
                </h3>
                <form method="GET" action="{{ route('admin.usuarios.index') }}" class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Perfil</label>
                        <select name="role" id="role" class="w-full form-select-scordon">
                            <option value="">Todos os perfis</option>
                            <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="suporte" {{ $role == 'suporte' ? 'selected' : '' }}>Suporte</option>
                            <option value="atendente" {{ $role == 'atendente' ? 'selected' : '' }}>Atendente</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="busca" class="block text-sm font-semibold text-gray-700 mb-2">Buscar</label>
                        <input type="text" name="busca" id="busca" class="w-full form-control-scordon" 
                               placeholder="Nome ou e-mail..." value="{{ $busca }}">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full btn-scordon">
                            <i class="fas fa-search mr-2"></i>
                            Filtrar
                        </button>
                    </div>
                    
                    <div class="flex items-end">
                        <a href="{{ route('admin.usuarios.index') }}" class="w-full btn-outline-scordon text-center">
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
                            Lista de Usuários
                        </h3>
                        <p class="text-gray-600">{{ $usuarios->total() }} usuários encontrados</p>
                    </div>
                    
                    <div class="flex space-x-4 mt-4 md:mt-0">
                        <a href="{{ route('admin.usuarios.create') }}" class="btn-verde">
                            <i class="fas fa-user-plus mr-2"></i>
                            Novo Usuário
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabela de Usuários -->
            @if($usuarios->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                    <div class="overflow-x-auto">
                        <table class="w-full table-scordon">
                            <thead class="gradient-bg text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold">Nome</th>
                                    <th class="px-6 py-4 text-left font-semibold">E-mail</th>
                                    <th class="px-6 py-4 text-center font-semibold">Perfil</th>
                                    <th class="px-6 py-4 text-center font-semibold">Status</th>
                                    <th class="px-6 py-4 text-center font-semibold">Último Login</th>
                                    <th class="px-6 py-4 text-center font-semibold">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($usuarios as $usuario)
                                    <tr class="hover:bg-scordon-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-800">
                                                {{ $usuario->aNome }}
                                            </div>
                                            @if($usuario->ID === auth()->id())
                                                <div class="text-sm text-blue-600">
                                                    <i class="fas fa-user-check mr-1"></i>
                                                    Você
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-700">{{ $usuario->aEmail }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($usuario->aRole === 'admin')
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <i class="fas fa-crown mr-1"></i>
                                                    Administrador
                                                </span>
                                            @elseif($usuario->aRole === 'suporte')
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <i class="fas fa-headset mr-1"></i>
                                                    Suporte
                                                </span>
                                            @else
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-user mr-1"></i>
                                                    Atendente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($usuario->isAtivo())
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
                                            <span class="text-sm text-gray-600">
                                                {{ $usuario->updated_at->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" 
                                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition-colors"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($usuario->ID !== auth()->id())
                                                    <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}" 
                                                          class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition-colors"
                                                                title="Excluir">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="bg-gray-300 text-gray-500 px-3 py-2 rounded-lg cursor-not-allowed" title="Você não pode excluir sua própria conta">
                                                        <i class="fas fa-ban"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginação -->
                    @if($usuarios->hasPages())
                        <div class="bg-gray-50 px-6 py-4 border-t">
                            <div class="pagination-info mb-4">
                                <i class="fas fa-info-circle text-scordon-500 mr-2"></i>
                                Mostrando {{ $usuarios->firstItem() }} até {{ $usuarios->lastItem() }} de {{ $usuarios->total() }} usuários
                            </div>
                            <div class="flex justify-center">
                                {{ $usuarios->withQueryString()->links('custom.pagination') }}
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <!-- Estado Vazio -->
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                    <div class="mb-6">
                        <i class="fas fa-users-cog text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Nenhum usuário encontrado</h3>
                    <p class="text-gray-600 mb-8">
                        Ajuste os filtros ou cadastre novos usuários no sistema.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('admin.usuarios.create') }}" class="btn-scordon">
                            <i class="fas fa-user-plus mr-2"></i>
                            Cadastrar Usuário
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
