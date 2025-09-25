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

<body class="bg-gradient-to-br from-gray-50 to-scordon-50 min-h-screen">
    <!-- Header com Gradient Amarelo -->
    <header class="gradient-bg shadow-lg">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('imagens/cordonpreto.png') }}" alt="Scordon" class="h-12 w-12">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">SCORDON</h1>
                        <p class="text-sm opacity-90">Sistema de Avaliação Comercial</p>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <section class="py-12">
        <div class="container mx-auto px-6">
        
        <!-- Card de Agradecimento -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <!-- Ícone de sucesso -->
                <div class="floating-animation mb-8">
                    <div class="bg-gradient-to-br from-green-400 to-green-600 w-24 h-24 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-check text-3xl text-white"></i>
                    </div>
                </div>
                
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-heart text-red-500 mr-3"></i>
                    Obrigado!
                </h1>
                
                <p class="text-xl text-gray-600 mb-8">
                    Sua avaliação foi recebida com sucesso!
                </p>
                
                <!-- Resumo da Avaliação -->
                <div class="bg-gray-50 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumo da sua avaliação:</h3>
                    
                    <div class="space-y-4">
                        <!-- Nota do atendimento -->
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Avaliação do atendimento:</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl">{{ $avaliacao->emoji_nota }}</span>
                                <span class="font-semibold text-gray-800">{{ $avaliacao->texto_nota }}</span>
                            </div>
                        </div>
                        
                        <!-- Expectativas -->
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Atendeu suas expectativas:</span>
                            <span class="font-semibold text-gray-800">{{ $avaliacao->texto_atendeu_expectativas }}</span>
                        </div>
                        
                        @if($avaliacao->aComentario)
                        <!-- Comentário -->
                        <div class="text-left">
                            <span class="text-gray-600 block mb-2">Seu comentário:</span>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <p class="text-gray-800 italic">"{{ $avaliacao->aComentario }}"</p>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Dados do atendimento -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Empresa:</span>
                                    <div class="font-semibold text-gray-800">{{ $avaliacao->empresa->aNome }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500">Vendedor:</span>
                                    <div class="font-semibold text-gray-800">{{ $avaliacao->atendente->aNome }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mensagem de agradecimento -->
                <div class="alert-scordon">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold mb-2">🙏 Agradecemos por sua colaboração!</h4>
                            <p>Sua opinião nos ajuda a melhorar sempre. Continuamos trabalhando para oferecer o melhor atendimento comercial possível.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Informações de contato -->
                <div class="mt-8 p-6 bg-scordon-50 rounded-xl">
                    <h4 class="font-semibold text-gray-800 mb-4">Precisa de mais alguma coisa?</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-phone text-scordon-600"></i>
                            <span>(44) 3028-4949</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fab fa-whatsapp text-scordon-600"></i>
                            <span>(44) 98811-7771</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-envelope text-scordon-600"></i>
                            <span>suporte@scordon.com.br</span>
                        </div>
                    </div>
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
