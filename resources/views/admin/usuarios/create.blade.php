<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Usuário - SCORDON</title>
    
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
                    <a href="{{ route('admin.usuarios.index') }}" class="hover:text-scordon-100 transition-colors">
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
        <div class="container mx-auto px-6 max-w-4xl">
            
            <!-- Header da Página -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-user-plus text-scordon-500 mr-3"></i>
                    Cadastrar Usuário
                </h1>
                <p class="text-xl text-gray-600 mb-6">Adicione um novo usuário ao sistema</p>
                <a href="{{ route('admin.usuarios.index') }}" class="btn-outline-scordon">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar à Lista
                </a>
            </div>

            <!-- Formulário -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Informação sobre Perfis -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Perfis de Usuário</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p><strong>Admin:</strong> Acesso total ao sistema</p>
                                    <p><strong>Suporte:</strong> Dashboard, relatórios e gerar links</p>
                                    <p><strong>Atendente:</strong> Apenas gerar links de avaliação</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nome -->
                    <div>
                        <label for="aNome" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-scordon-500 mr-2"></i>
                            Nome Completo *
                        </label>
                        <input type="text" 
                               name="aNome" 
                               id="aNome" 
                               value="{{ old('aNome') }}"
                               class="w-full form-control-scordon @error('aNome') border-red-500 @enderror" 
                               placeholder="Nome completo do usuário"
                               required>
                        @error('aNome')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- E-mail -->
                    <div>
                        <label for="aEmail" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-scordon-500 mr-2"></i>
                            E-mail *
                        </label>
                        <input type="email" 
                               name="aEmail" 
                               id="aEmail" 
                               value="{{ old('aEmail') }}"
                               class="w-full form-control-scordon @error('aEmail') border-red-500 @enderror" 
                               placeholder="email@scordon.com.br"
                               required>
                        @error('aEmail')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="aSenha" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock text-scordon-500 mr-2"></i>
                                Senha *
                            </label>
                            <input type="password" 
                                   name="aSenha" 
                                   id="aSenha" 
                                   class="w-full form-control-scordon @error('aSenha') border-red-500 @enderror" 
                                   placeholder="Mínimo 6 caracteres"
                                   required>
                            @error('aSenha')
                                <p class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="aSenha_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock text-scordon-500 mr-2"></i>
                                Confirmar Senha *
                            </label>
                            <input type="password" 
                                   name="aSenha_confirmation" 
                                   id="aSenha_confirmation" 
                                   class="w-full form-control-scordon" 
                                   placeholder="Repita a senha"
                                   required>
                        </div>
                    </div>

                    <!-- Perfil -->
                    <div>
                        <label for="aRole" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-tag text-scordon-500 mr-2"></i>
                            Perfil do Usuário *
                        </label>
                        <select name="aRole" id="aRole" class="w-full form-select-scordon @error('aRole') border-red-500 @enderror" required>
                            <option value="">Selecione o perfil...</option>
                            <option value="admin" {{ old('aRole') == 'admin' ? 'selected' : '' }}>
                                👑 Administrador - Acesso total
                            </option>
                            <option value="suporte" {{ old('aRole') == 'suporte' ? 'selected' : '' }}>
                                🎧 Suporte - Dashboard e relatórios
                            </option>
                            <option value="atendente" {{ old('aRole') == 'atendente' ? 'selected' : '' }}>
                                👤 Atendente - Gerar links
                            </option>
                        </select>
                        @error('aRole')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="nStatus" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-toggle-on text-scordon-500 mr-2"></i>
                            Status *
                        </label>
                        <select name="nStatus" id="nStatus" class="w-full form-select-scordon @error('nStatus') border-red-500 @enderror" required>
                            <option value="0" {{ old('nStatus', '0') == '0' ? 'selected' : '' }}>
                                ✅ Ativo
                            </option>
                            <option value="99" {{ old('nStatus') == '99' ? 'selected' : '' }}>
                                ❌ Inativo
                            </option>
                        </select>
                        @error('nStatus')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Botões -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" class="flex-1 btn-scordon">
                            <i class="fas fa-save mr-2"></i>
                            Cadastrar Usuário
                        </button>
                        <a href="{{ route('admin.usuarios.index') }}" class="flex-1 btn-outline-scordon text-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
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
