<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Avaliação SCORDON</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- CSS Global Scordon -->
    <link href="{{ asset('css/scordon.css') }}" rel="stylesheet">
    
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
                            400: '#FBBF24', // Amarelo principal Scordon
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
    
            <style>
        .rating-stars {
            font-size: 2rem;
            color: #e5e7eb;
            cursor: pointer;
            transition: all 0.2s;
        }
        
         .rating-stars .star.active,
         .rating-stars .star:hover {
             color: #FBBF24;
             transform: scale(1.1);
         }
         
         .gradient-bg {
             background: linear-gradient(135deg, #FBBF24 0%, #F59E0B 100%);
         }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(251, 191, 36, 0.2);
        }
        
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
            </style>
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
                    @if(auth()->check())
                        <a href="{{ route('admin.index') }}" class="hover:text-scordon-100 transition-colors">
                            <i class="fas fa-link mr-2"></i>Gerar Links
                        </a>
                        @if(auth()->user()->hasAnyRole(['admin', 'suporte']))
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-scordon-100 transition-colors">
                                <i class="fas fa-chart-bar mr-2"></i>Dashboard
                            </a>
                            <a href="{{ route('admin.relatorio') }}" class="hover:text-scordon-100 transition-colors">
                                <i class="fas fa-file-alt mr-2"></i>Relatórios
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
                    @else
                        <a href="{{ route('login') }}" class="hover:text-scordon-100 transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                    @endif
                </div>
            </div>
                </nav>
        </header>

    <!-- Hero Section -->
    <section class="py-20">
        <div class="container mx-auto px-6 text-center">
            <div class="floating-animation mb-8">
                <img src="{{ asset('imagens/cordonpreto.png') }}" alt="Scordon Logo" class="h-24 w-24 mx-auto mb-6">
            </div>
            
             <h1 class="text-5xl md:text-6xl font-bold text-gray-800 mb-4">
                 Sistema de <span class="text-scordon-400">Avaliação</span>
             </h1>
            
            <div class="text-scordon-500 text-xl font-semibold mb-2">SCORDON</div>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                <span class="font-semibold text-scordon-500">Desde 1993</span> transformando a gestão com tecnologia de ponta
                <br>
                <span class="text-lg">Avalie nosso atendimento e ajude-nos a melhorar ainda mais</span>
            </p>

            <!-- Cards Principais -->
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-16">
                <!-- Gerar Links -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-xl border-2 border-scordon-200">
                    <div class="bg-gradient-to-br from-scordon-400 to-scordon-600 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-link text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Gerar Links</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Crie links únicos de avaliação para enviar aos clientes após o atendimento de forma rápida e segura.
                    </p>
                     <a href="{{ route('admin.index') }}" 
                        class="inline-flex items-center px-6 py-3 bg-scordon-400 hover:bg-scordon-500 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-magic mr-2"></i>
                        Gerar Link
                    </a>
                </div>

                <!-- Dashboard -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-xl border-2 border-green-200">
                    <div class="bg-gradient-to-br from-green-400 to-green-600 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-bar text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Dashboard</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Visualize estatísticas em tempo real e gráficos interativos das avaliações recebidas.
                    </p>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-chart-line mr-2"></i>
                        Ver Dashboard
                    </a>
                </div>

                <!-- Relatórios -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-xl border-2 border-blue-200">
                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-file-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Relatórios</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Acesse relatórios detalhados e exporte dados das avaliações em diversos formatos.
                    </p>
                    <a href="{{ route('admin.relatorio') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-download mr-2"></i>
                        Ver Relatórios
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Como Funciona -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                 <h2 class="text-4xl font-bold text-gray-800 mb-4">
                     <i class="fas fa-cogs text-scordon-400 mr-3"></i>
                     Como Funciona o Sistema
                 </h2>
                <p class="text-xl text-gray-600">Processo simples e eficiente em 3 passos</p>
            </div>

            <div class="grid md:grid-cols-3 gap-12 max-w-5xl mx-auto">
                <!-- Passo 1 -->
                <div class="text-center">
                    <div class="relative mb-8">
                         <div class="bg-gradient-to-br from-scordon-400 to-scordon-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto shadow-lg">
                            <span class="text-3xl font-bold text-white">1</span>
                        </div>
                        <div class="absolute -top-2 -right-2 bg-scordon-200 w-6 h-6 rounded-full animate-ping"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Gerar Link</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Selecione a empresa e o atendente responsável para gerar um link único e seguro de avaliação.
                    </p>
                </div>

                <!-- Passo 2 -->
                <div class="text-center">
                    <div class="relative mb-8">
                        <div class="bg-gradient-to-br from-green-400 to-green-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto shadow-lg">
                            <span class="text-3xl font-bold text-white">2</span>
                        </div>
                        <div class="absolute -top-2 -right-2 bg-green-200 w-6 h-6 rounded-full animate-ping"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Enviar ao Cliente</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Copie o link gerado e envie para o cliente ao final do atendimento via WhatsApp ou email.
                    </p>
                </div>

                <!-- Passo 3 -->
                <div class="text-center">
                    <div class="relative mb-8">
                        <div class="bg-gradient-to-br from-blue-400 to-blue-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto shadow-lg">
                            <span class="text-3xl font-bold text-white">3</span>
                        </div>
                        <div class="absolute -top-2 -right-2 bg-blue-200 w-6 h-6 rounded-full animate-ping"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Acompanhar Resultados</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Monitore as avaliações recebidas através do dashboard interativo e relatórios detalhados.
                    </p>
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
    </body>
</html>
