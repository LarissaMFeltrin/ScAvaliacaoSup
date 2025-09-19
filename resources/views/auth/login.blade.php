<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistema SCORDON</title>
    
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
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="floating-animation mb-6">
                    <img src="{{ asset('imagens/cordonpreto.png') }}" alt="Scordon Logo" class="h-20 w-20 mx-auto">
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Sistema SCORDON</h1>
                <p class="text-lg text-gray-600">Faça login para continuar</p>
            </div>

            <!-- Card de Login -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- E-mail -->
                    <div>
                        <label for="aEmail" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-scordon-500 mr-2"></i>
                            E-mail
                        </label>
                        <input type="email" 
                               id="aEmail" 
                               name="aEmail" 
                               value="{{ old('aEmail') }}"
                               class="w-full form-control-scordon @error('aEmail') border-red-500 @enderror" 
                               placeholder="seu@email.com"
                               required>
                        @error('aEmail')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="aSenha" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-scordon-500 mr-2"></i>
                            Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="aSenha" 
                                   name="aSenha" 
                                   class="w-full form-control-scordon pr-10 @error('aSenha') border-red-500 @enderror" 
                                   placeholder="••••••••"
                                   required>
                            <button type="button" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggle-icon"></i>
                            </button>
                        </div>
                        @error('aSenha')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Lembrar-me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember" 
                                   class="h-4 w-4 text-scordon-500 focus:ring-scordon-400 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Lembrar-me
                            </label>
                        </div>
                    </div>

                    <!-- Botão Login -->
                    <button type="submit" class="w-full btn-scordon text-lg py-3">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Entrar
                    </button>
                </form>
            </div>

            <!-- Informações -->
            <div class="bg-white/70 rounded-xl p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Níveis de Acesso
                </h3>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div class="text-center">
                        <div class="bg-red-100 text-red-800 px-3 py-2 rounded-lg font-semibold">
                            <i class="fas fa-crown mr-1"></i>
                            Admin
                        </div>
                        <p class="text-gray-600 mt-1">Acesso total</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-100 text-blue-800 px-3 py-2 rounded-lg font-semibold">
                            <i class="fas fa-headset mr-1"></i>
                            Suporte
                        </div>
                        <p class="text-gray-600 mt-1">Relatórios</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-green-100 text-green-800 px-3 py-2 rounded-lg font-semibold">
                            <i class="fas fa-user mr-1"></i>
                            Atendente
                        </div>
                        <p class="text-gray-600 mt-1">Gerar links</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <img src="{{ asset('imagens/cordonamarelo.png') }}" alt="Scordon" class="h-8 w-8">
                    <div>
                        <div class="text-lg font-bold">Sistemas SCORDON</div>
                        <div class="text-scordon-300 text-sm">Desde 1993 • Mais de 30 anos de experiência</div>
                    </div>
                </div>
                <div class="text-center md:text-right text-sm">
                    <div class="text-gray-400">
                        © {{ date('Y') }} Sistemas SCORDON. Todos os direitos reservados.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/scordon.js') }}"></script>
    
    <script>
        // Mostrar/ocultar senha
        function togglePassword() {
            const passwordField = document.getElementById('aSenha');
            const toggleIcon = document.getElementById('toggle-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Mostrar notificações
        @if(session('sucesso'))
            mostrarNotificacao('{{ session('sucesso') }}', 'sucesso');
        @endif

        @if(session('erro'))
            mostrarNotificacao('{{ session('erro') }}', 'erro');
        @endif

        // Animação da logo
        $(document).ready(function() {
            $('.floating-animation').addClass('animate-pulse');
        });
    </script>

    <style>
        .floating-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</body>
</html>
