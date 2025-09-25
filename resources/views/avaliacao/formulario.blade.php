<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avalie nosso Atendimento - SCORDON</title>
    
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
            </div>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <section class="py-12">
        <div class="container mx-auto px-6">
        
        <!-- Header da Avaliação -->
        <div class="text-center mb-12">
            <div class="floating-animation mb-6">
                <img src="{{ asset('imagens/cordonpreto.png') }}" alt="Scordon" class="h-20 w-20 mx-auto">
            </div>
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-star text-scordon-500 mr-3"></i>
                    Avalie nosso Atendimento
                </h1>
                <div class="text-scordon-600 font-semibold text-lg mb-4">SCORDON</div>
                <div class="text-gray-600 mb-6">
                    <div class="text-xl font-semibold text-gray-800">{{ $avaliacao->empresa->aNome }}</div>
                    <div class="text-lg">
                        <strong>Atendente:</strong> {{ $avaliacao->atendente->aNome }}
                    </div>
                    <div class="text-sm text-gray-500 mt-2">
                        Desde 1993 • Sua opinião é muito importante para nós!
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário de Avaliação -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form action="{{ route('avaliacao.enviar', $avaliacao->aToken) }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- Dados do Cliente -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="aNomeCliente" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-scordon-500 mr-2"></i>
                                Seu Nome (opcional)
                            </label>
                            <input type="text" 
                                   class="w-full form-control-scordon" 
                                   id="aNomeCliente" 
                                   name="aNomeCliente" 
                                   value="{{ old('aNomeCliente') }}" 
                                   placeholder="Digite seu nome">
                        </div>
                        <div>
                            <label for="aEmailCliente" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-scordon-500 mr-2"></i>
                                Seu E-mail (opcional)
                            </label>
                            <input type="email" 
                                   class="w-full form-control-scordon" 
                                   id="aEmailCliente" 
                                   name="aEmailCliente" 
                                   value="{{ old('aEmailCliente') }}" 
                                   placeholder="Digite seu e-mail">
                        </div>
                    </div>

                    <!-- Avaliação por Estrelas -->
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">
                            Como você avalia nosso atendimento?
                        </h3>
                        <div class="rating-stars flex justify-center space-x-2 mb-4" id="rating-stars">
                            <span class="star" data-rating="1"><i class="fas fa-star"></i></span>
                            <span class="star" data-rating="2"><i class="fas fa-star"></i></span>
                            <span class="star" data-rating="3"><i class="fas fa-star"></i></span>
                            <span class="star" data-rating="4"><i class="fas fa-star"></i></span>
                            <span class="star" data-rating="5"><i class="fas fa-star"></i></span>
                        </div>
                        <div class="text-lg font-semibold" id="rating-text">Clique nas estrelas para avaliar</div>
                        <input type="hidden" name="nNotaAtendimento" id="nota-input" value="{{ old('nNotaAtendimento') }}">
                    </div>

                    <!-- Comentário -->
                    <div>
                        <label for="aComentario" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-comment text-scordon-500 mr-2"></i>
                            Comentários (opcional)
                        </label>
                        <textarea class="w-full form-control-scordon" 
                                  id="aComentario" 
                                  name="aComentario" 
                                  rows="4" 
                                  placeholder="Compartilhe sua experiência conosco...">{{ old('aComentario') }}</textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" 
                                class="btn-scordon text-lg px-8 py-4 btn-enviar" 
                                id="enviar-btn" 
                                disabled>
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar Avaliação
                        </button>
                    </div>
                </form>
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
    // Inicializar contador de caracteres
    initContadorCaracteres('#aComentario', 1000);
    
    // O sistema de rating já é inicializado automaticamente pelo JS global
    // Apenas definir valor inicial se existir
    const notaInicial = {{ old('nNotaAtendimento', 0) }};
    if (notaInicial > 0) {
        $('#nota-input').val(notaInicial);
        // Trigger do sistema global
        $(`.star[data-rating="${notaInicial}"]`).click();
    }
});
</script>
</body>
</html>
