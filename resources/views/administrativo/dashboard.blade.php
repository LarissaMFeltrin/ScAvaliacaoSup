<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Avaliações SCORDON</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- CSS Global Scordon -->
    <link href="{{ asset('css/scordon.css') }}" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-scordon-100 transition-colors font-semibold">
                        <i class="fas fa-chart-bar mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.relatorio') }}" class="hover:text-scordon-100 transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>Relatórios
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.usuarios.index') }}" class="hover:text-scordon-100 transition-colors">
                            <i class="fas fa-users-cog mr-2"></i>Usuários
                        </a>
                    @endif
                    
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
            
            <!-- Header do Dashboard -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-bar text-scordon-500 mr-3"></i>
                    Dashboard de Avaliações
                </h1>
                <p class="text-xl text-gray-600 mb-6">Acompanhe o desempenho do seu atendimento</p>
                <a href="{{ route('admin.relatorio') }}" class="btn-azul">
                    <i class="fas fa-file-alt mr-2"></i>
                    Ver Relatório Detalhado
                </a>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-filter text-scordon-500 mr-2"></i>
                    Filtros
                </h3>
                <form method="GET" action="{{ route('admin.dashboard') }}" class="grid md:grid-cols-5 gap-4">
                    <div>
                        <label for="nIdEmpresa" class="block text-sm font-semibold text-gray-700 mb-2">Empresa</label>
                        <select name="nIdEmpresa" id="nIdEmpresa" class="w-full form-select-scordon">
                            <option value="">Todas as empresas</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->ID }}" {{ $idEmpresa == $empresa->ID ? 'selected' : '' }}>
                                    {{ $empresa->aNome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="nIdAtendente" class="block text-sm font-semibold text-gray-700 mb-2">Atendente</label>
                        <select name="nIdAtendente" id="nIdAtendente" class="w-full form-select-scordon">
                            <option value="">Todos os atendentes</option>
                            @foreach($atendentes as $atendente)
                                <option value="{{ $atendente->ID }}" {{ $idAtendente == $atendente->ID ? 'selected' : '' }}>
                                    {{ $atendente->aNome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="dDataInicio" class="block text-sm font-semibold text-gray-700 mb-2">Data Inicial</label>
                        <input type="date" name="dDataInicio" id="dDataInicio" class="w-full form-control-scordon" value="{{ $dataInicio }}">
                    </div>
                    
                    <div>
                        <label for="dDataFim" class="block text-sm font-semibold text-gray-700 mb-2">Data Final</label>
                        <input type="date" name="dDataFim" id="dDataFim" class="w-full form-control-scordon" value="{{ $dataFim }}">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full btn-scordon">
                            <i class="fas fa-filter mr-2"></i>
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Cards de Estatísticas -->
            <div class="grid md:grid-cols-4 gap-6 mb-12">
                <div class="bg-white rounded-2xl shadow-xl p-6 border-l-4 border-scordon-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total de Avaliações</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalAvaliacoes }}</p>
                        </div>
                        <div class="bg-scordon-100 p-3 rounded-full">
                            <i class="fas fa-star text-2xl text-scordon-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Nota Média</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($notaMedia, 1) }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-chart-line text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Muito Satisfeitos</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $distribuicaoNotas[5] ?? 0 }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-thumbs-up text-2xl text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Taxa de Satisfação</p>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ $totalAvaliacoes > 0 ? number_format((($distribuicaoNotas[4] ?? 0) + ($distribuicaoNotas[5] ?? 0)) / $totalAvaliacoes * 100, 1) : 0 }}%
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-heart text-2xl text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico e Avaliações Recentes -->
            <div class="grid lg:grid-cols-2 gap-8 mb-12">
                <!-- Gráfico de Distribuição -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-chart-pie text-scordon-500 mr-2"></i>
                        Distribuição das Notas
                    </h3>
                    <div class="relative h-64">
                        <canvas id="graficoNotas"></canvas>
                    </div>
                </div>
                
                <!-- Avaliações Recentes -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-clock text-scordon-500 mr-2"></i>
                        Avaliações Recentes
                    </h3>
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        @if($avaliacoesRecentes->count() > 0)
                            @foreach($avaliacoesRecentes as $avaliacao)
                                <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-800">{{ $avaliacao->empresa?->aNome ?? 'Empresa não encontrada' }}</h4>
                                            <p class="text-sm text-gray-600">
                                                <strong>Atendente:</strong> {{ $avaliacao->atendente?->aNome ?? 'Atendente não encontrado' }}
                                            </p>
                                            @if($avaliacao->aNomeCliente)
                                                <p class="text-sm text-gray-600">
                                                    <strong>Cliente:</strong> {{ $avaliacao->aNomeCliente }}
                                                </p>
                                            @endif
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $avaliacao->dAvaliadoEm?->format('d/m/Y H:i') ?? 'Data não disponível' }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex space-x-1 mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= ($avaliacao->nNota ?? 0))
                                                        <i class="fas fa-star text-scordon-500"></i>
                                                    @else
                                                        <i class="far fa-star text-gray-300"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-xs px-2 py-1 rounded-full 
                                                @if(($avaliacao->nNota ?? 0) >= 4) bg-green-100 text-green-800
                                                @elseif(($avaliacao->nNota ?? 0) == 3) bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $avaliacao->texto_nota ?? 'Não avaliado' }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($avaliacao->aComentario)
                                        <div class="mt-3 p-3 bg-gray-100 rounded-lg">
                                            <p class="text-sm text-gray-700 italic">"{{ Str::limit($avaliacao->aComentario, 100) }}"</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Nenhuma avaliação encontrada</p>
                            </div>
                        @endif
                    </div>
                </div>
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
    $(document).ready(function() {
        // Filtro de empresa/atendente
        $('#nIdEmpresa').change(function() {
            const idEmpresa = $(this).val();
            
            if (idEmpresa) {
                $.get(`/admin/api/empresas/${idEmpresa}/atendentes`, function(data) {
                    let opcoes = '<option value="">Todos os atendentes</option>';
                    data.forEach(function(atendente) {
                        opcoes += `<option value="${atendente.ID}">${atendente.aNome}</option>`;
                    });
                    $('#nIdAtendente').html(opcoes);
                });
            } else {
                $('#nIdAtendente').html('<option value="">Todos os atendentes</option>');
            }
        });

        // Gráfico de distribuição
        const ctx = document.getElementById('graficoNotas');
        if (ctx) {
            const dadosNota = @json($distribuicaoNotas);
            
            // Preparar dados dinâmicos (apenas notas que existem)
            const todasAsNotas = [
                { nota: 1, label: 'Muito Insatisfeito (1)', cor: '#dc3545' },
                { nota: 2, label: 'Insatisfeito (2)', cor: '#fd7e14' },
                { nota: 3, label: 'Neutro (3)', cor: '#FBBF24' },
                { nota: 4, label: 'Satisfeito (4)', cor: '#10B981' },
                { nota: 5, label: 'Muito Satisfeito (5)', cor: '#059669' }
            ];
            
            // Filtrar apenas notas com dados
            const notasComDados = todasAsNotas.filter(item => (dadosNota[item.nota] || 0) > 0);
            
            // Se não há dados, mostrar uma mensagem
            if (notasComDados.length === 0) {
                $('#graficoNotas').parent().html(`
                    <div class="flex items-center justify-center h-64 text-gray-400">
                        <div class="text-center">
                            <i class="fas fa-chart-pie text-6xl mb-4"></i>
                            <p class="text-lg">Nenhuma avaliação encontrada</p>
                            <p class="text-sm">Gere links e colete avaliações para ver o gráfico</p>
                        </div>
                    </div>
                `);
                return;
            }
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: notasComDados.map(item => item.label),
                    datasets: [{
                        data: notasComDados.map(item => dadosNota[item.nota]),
                        backgroundColor: notasComDados.map(item => item.cor),
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12,
                                    weight: '500'
                                },
                                color: '#374151'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed * 100) / total).toFixed(1);
                                    return `${context.label}: ${context.parsed} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
</body>
</html>