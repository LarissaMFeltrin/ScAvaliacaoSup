<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Obrigado pela sua Avaliação - SCORDON</title>
    
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

<body class="bg-gradient-to-br from-green-50 to-scordon-50 min-h-screen">
    <!-- Header com Gradient Amarelo -->
    <header class="gradient-bg shadow-lg">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('imagens/cordonpreto.png') }}" alt="Scordon" class="h-12 w-12">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">SCORDON</h1>
                        <p class="text-sm opacity-90">Sistema de Avaliação</p>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <section class="py-12">
        <div class="container mx-auto px-6">
        
        <div class="text-center mb-12">
            <div class="floating-animation mb-8">
                <div class="bg-gradient-to-br from-green-400 to-green-600 w-24 h-24 rounded-full flex items-center justify-center mx-auto shadow-2xl">
                    <i class="fas fa-check-circle text-4xl text-white"></i>
                </div>
            </div>
            
            <h1 class="text-5xl font-bold text-green-600 mb-4">Obrigado!</h1>
            <p class="text-2xl text-gray-700 mb-8">Sua avaliação foi registrada com sucesso.</p>
            
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
                <div class="flex items-center justify-center mb-6">
                    <img src="{{ asset('imagens/cordonpreto.png') }}" alt="Scordon" class="h-12 w-12 mr-4">
                    <div class="text-left">
                        <div class="text-xl font-bold text-gray-800">SCORDON</div>
                        <div class="text-sm text-gray-600">Desde 1993</div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-scordon-50 to-yellow-50 p-6 rounded-xl mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">Resumo da sua avaliação</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Empresa:</span>
                            <span class="font-bold text-gray-800">{{ $avaliacao->empresa->aNome }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Atendente:</span>
                            <span class="font-bold text-gray-800">{{ $avaliacao->atendente->aNome }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Sua nota:</span>
                            <div class="flex items-center">
                                <div class="rating-display flex space-x-1 mr-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $avaliacao->nNota)
                                            <i class="fas fa-star text-scordon-500"></i>
                                        @else
                                            <i class="far fa-star text-gray-300"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="font-bold text-scordon-600">{{ $avaliacao->texto_nota }}</span>
                            </div>
                        </div>
                        
                        @if($avaliacao->aComentario)
                            <div class="border-t pt-4">
                                <span class="text-gray-600 font-medium block mb-2">Seu comentário:</span>
                                <div class="bg-white p-4 rounded-lg border-l-4 border-scordon-400">
                                    <em class="text-gray-700">"{{ $avaliacao->aComentario }}"</em>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center border-t pt-4">
                            <span class="text-gray-600 font-medium">Data da avaliação:</span>
                            <span class="font-semibold text-gray-800">{{ $avaliacao->dAvaliadoEm->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-400 to-pink-400 text-white rounded-full">
                        <i class="fas fa-heart mr-2 animate-pulse"></i>
                        <span class="font-semibold">Sua opinião é muito importante para nós!</span>
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
</body>
</html>
