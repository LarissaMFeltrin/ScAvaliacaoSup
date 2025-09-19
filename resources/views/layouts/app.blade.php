<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistema de Avaliação de Atendimento')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS (para componentes específicos) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                            50: '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#F59E0B',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    }
                }
            }
        }
    </script>
    
    @yield('styles')
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-scordon">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('imagens/cordonpreto.png') }}" alt="Scordon" height="35" class="me-3">
                <div>
                    <div style="font-size: 1.2rem; font-weight: bold;">SCORDON</div>
                    <small style="font-size: 0.75rem; opacity: 0.9;">Sistema de Avaliação</small>
                </div>
            </a>
            
            <div class="navbar-nav ms-auto">
                @if(request()->is('admin*'))
                    <a class="nav-link" href="{{ route('admin.index') }}">
                        <i class="fas fa-link me-1"></i>
                        Gerar Links
                    </a>
                    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'suporte']))
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-bar me-1"></i>
                            Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('admin.relatorio') }}">
                            <i class="fas fa-file-alt me-1"></i>
                            Relatórios
                        </a>
                    @endif
                @endif
                
                @if(auth()->check())
                    <!-- Informações do Usuário -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i>{{ auth()->user()->aNome }}
                            <small class="text-muted">({{ auth()->user()->nome_role }})</small>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Rodapé Scordon -->
    <footer class="mt-5 py-3 bg-gray-800 text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('imagens/cordonamarelo.png') }}" alt="Scordon" height="25" class="me-2">
                        <div>
                            <small><strong>SCORDON</strong> • Desde 1993</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    <small>© {{ date('Y') }} Sistemas SCORDON</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JS Global Scordon -->
    <script src="{{ asset('js/scordon.js') }}"></script>
    
    @yield('scripts')
</body>
</html>

