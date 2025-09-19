<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Atendente - SCORDON</title>
    
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
                    <a href="{{ route('admin.atendentes.index') }}" class="hover:text-scordon-100 transition-colors">
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
        <div class="container mx-auto px-6 max-w-4xl">
            
            <!-- Header da Página -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-user-edit text-scordon-500 mr-3"></i>
                    Editar Atendente
                </h1>
                <p class="text-xl text-gray-600 mb-6">Altere os dados do funcionário SCORDON</p>
                <a href="{{ route('admin.atendentes.index') }}" class="btn-outline-scordon">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar à Lista
                </a>
            </div>

            <!-- Formulário -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form method="POST" action="{{ route('admin.atendentes.update', $atendente) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informação sobre Atendentes -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Funcionário SCORDON</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Este atendente é <strong>funcionário da SCORDON</strong> e pode atender chamados de qualquer empresa cliente.</p>
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
                               value="{{ old('aNome', $atendente->aNome) }}"
                               class="w-full form-control-scordon @error('aNome') border-red-500 @enderror" 
                               placeholder="Nome completo do atendente"
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
                            E-mail
                        </label>
                        <input type="email" 
                               name="aEmail" 
                               id="aEmail" 
                               value="{{ old('aEmail', $atendente->aEmail) }}"
                               class="w-full form-control-scordon @error('aEmail') border-red-500 @enderror" 
                               placeholder="email@empresa.com (opcional)">
                        @error('aEmail')
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
                            <option value="0" {{ old('nStatus', $atendente->nStatus) == '0' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle"></i> Ativo
                            </option>
                            <option value="99" {{ old('nStatus', $atendente->nStatus) == '99' ? 'selected' : '' }}>
                                <i class="fas fa-times-circle"></i> Inativo
                            </option>
                        </select>
                        @error('nStatus')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Informações Adicionais -->
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Informações do Atendente
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-semibold text-gray-700">Empresa:</span>
                                <span class="text-gray-600">SCORDON (Funcionário)</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Avaliações:</span>
                                <span class="text-gray-600">{{ $atendente->avaliacoes()->count() }} avaliações</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Criado em:</span>
                                <span class="text-gray-600">{{ $atendente->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Última atualização:</span>
                                <span class="text-gray-600">{{ $atendente->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" class="flex-1 btn-scordon">
                            <i class="fas fa-save mr-2"></i>
                            Salvar Alterações
                        </button>
                        <a href="{{ route('admin.atendentes.index') }}" class="flex-1 btn-outline-scordon text-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>

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
