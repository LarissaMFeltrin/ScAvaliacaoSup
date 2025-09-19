<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerar Link de Avaliação - SCORDON</title>
    
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
                    <a href="{{ route('admin.index') }}" class="hover:text-scordon-100 transition-colors font-semibold">
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
            <!-- Header da Página -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-link text-scordon-500 mr-3"></i>
                    Gerar Link de Avaliação
                </h1>
                <p class="text-xl text-gray-600">Crie links únicos para seus clientes avaliarem o atendimento</p>
            </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Formulário -->
        <div id="card-formulario" class="card-scordon p-8">
            <div class="text-center mb-8">
                <div class="bg-gradient-to-br from-scordon-400 to-scordon-600 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-magic text-2xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Gerar Novo Link</h2>
                <p class="text-gray-600">Preencha os dados para gerar o link de avaliação</p>
            </div>
            
            <form id="gerar-form" class="space-y-6">
                <div>
                    <label for="nIdEmpresa" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-building text-scordon-500 mr-2"></i>
                        Empresa *
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="busca-empresa" 
                               class="w-full form-control-scordon" 
                               placeholder="Digite o nome da empresa..." 
                               autocomplete="off">
                        <div id="lista-empresas" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                            <!-- Lista de empresas será carregada aqui -->
                        </div>
                        <input type="hidden" id="nIdEmpresa" name="nIdEmpresa" required>
                    </div>
                    <small class="text-gray-500 mt-1 block">
                        <i class="fas fa-search mr-1"></i>
                        Digite para buscar as empresas
                    </small>
                </div>

                <div>
                    <label for="nIdAtendente" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-scordon-500 mr-2"></i>
                        Atendente *
                    </label>
                    <select class="w-full form-select-scordon" id="nIdAtendente" name="nIdAtendente" required disabled>
                        <option value="">Primeiro selecione uma empresa...</option>
                    </select>
                    @if(auth()->user()->hasAnyRole(['atendente', 'suporte', 'admin']))
                        <small class="text-gray-500 mt-1 block">
                            <i class="fas fa-info-circle mr-1"></i>
                            Seu nome será selecionado automaticamente
                        </small>
                    @endif
                </div>

                <button type="submit" class="w-full btn-scordon" id="gerar-btn" disabled>
                    <i class="fas fa-magic mr-2"></i>
                    Gerar Link de Avaliação
                </button>
            </form>
        </div>

        <!-- Card de Resultado -->
        <div id="resultado-card" class="card-scordon p-8 hidden">
            <div class="text-center mb-8">
                <div class="bg-gradient-to-br from-green-400 to-green-600 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4 pulse-animation">
                    <i class="fas fa-check-circle text-2xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Link Gerado com Sucesso!</h2>
                <p class="text-gray-600">
                    @if(auth()->user()->isAtendente())
                        Seu link pessoal de avaliação está pronto para envio ao cliente
                    @else
                        Link de avaliação está pronto para uso
                    @endif
                </p>
            </div>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-link text-scordon-500 mr-2"></i>
                        Link de Avaliação:
                    </label>
                    <div class="flex">
                        <input type="text" class="flex-1 form-control-scordon rounded-r-none" id="link-gerado" readonly>
                        <button class="btn-verde rounded-l-none btn-copiar" type="button" id="copiar-btn" data-target="#link-gerado">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <small class="text-gray-500 mt-1 block">Clique no botão para copiar o link</small>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="font-semibold text-gray-800 mb-4">Detalhes da Avaliação:</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Empresa:</span>
                            <span class="font-semibold" id="resultado-empresa"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Atendente:</span>
                            <span class="font-semibold" id="resultado-atendente"></span>
                        </div>
                    </div>
                </div>

                <div class="alert-scordon">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold mb-2">Instruções:</h4>
                            <p>Copie este link e envie para o cliente ao final do atendimento. O cliente poderá avaliar o atendimento através deste link único e seguro.</p>
                        </div>
                    </div>
                </div>

                <button class="w-full btn-scordon" id="novo-link-btn">
                    <i class="fas fa-plus mr-2"></i>
                    Gerar Novo Link
                </button>
            </div>
        </div>

        <!-- Card de Informações -->
        <div id="info-card" class="card-scordon p-8">
            <div class="text-center mb-8">
                <div class="bg-gradient-to-br from-blue-400 to-blue-600 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-question-circle text-2xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Como Usar</h2>
                <p class="text-gray-600">Siga estes passos simples</p>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="bg-scordon-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">1</div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Selecione a empresa</h3>
                        <p class="text-gray-600 text-sm">Escolha a empresa responsável pelo atendimento</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="bg-scordon-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">2</div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Escolha o atendente</h3>
                        <p class="text-gray-600 text-sm">Selecione quem foi responsável pelo atendimento</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="bg-scordon-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">3</div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Gere e envie</h3>
                        <p class="text-gray-600 text-sm">Clique em gerar, copie o link e envie para o cliente</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-yellow-600 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-800">Segurança</h4>
                        <p class="text-yellow-700 text-sm">Cada link é único e pode ser usado apenas uma vez.</p>
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
<script>
$(document).ready(function() {
    // Configurar CSRF token para todas as requisições AJAX
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
    
    // Carregar empresas
    carregarEmpresas();
    
    // Implementar busca dinâmica de empresas
    let empresasData = [];
    
    // Busca em tempo real
    $('#busca-empresa').on('input', function() {
        const termo = $(this).val().toLowerCase();
        
        if (termo.length < 2) {
            $('#lista-empresas').addClass('hidden');
            return;
        }
        
        const empresasFiltradas = empresasData.filter(empresa => 
            empresa.aNome.toLowerCase().includes(termo)
        );
        
        mostrarListaEmpresas(empresasFiltradas);
    });
    
    // Esconder lista ao clicar fora
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#busca-empresa, #lista-empresas').length) {
            $('#lista-empresas').addClass('hidden');
        }
    });

    // Evento de mudança de empresa (hidden field)
    $('#nIdEmpresa').change(function() {
        const idEmpresa = $(this).val();
        if (idEmpresa) {
            carregarAtendentes(idEmpresa);
        } else {
            $('#nIdAtendente').html('<option value="">Primeiro selecione uma empresa...</option>').prop('disabled', true);
            $('#gerar-btn').prop('disabled', true);
        }
    });

    // Evento de mudança de atendente
    $('#nIdAtendente').change(function() {
        const idAtendente = $(this).val();
        $('#gerar-btn').prop('disabled', !idAtendente);
    });

    // Evento de geração de link
    $('#gerar-form').submit(function(e) {
        e.preventDefault();
        gerarLink();
    });

    // Evento de cópia
    $('#copiar-btn').click(function() {
        copiarParaClipboard();
    });

    // Evento de novo link
    $('#novo-link-btn').click(function() {
        resetarForm();
    });

    function carregarEmpresas() {
        $.get('{{ route("admin.empresas") }}', function(data) {
            empresasData = data; // Armazenar para busca
            console.log(`${data.length} empresas carregadas para busca`);
        }).fail(function() {
            alert('Erro ao carregar empresas');
        });
    }
    
    function mostrarListaEmpresas(empresas) {
        if (empresas.length === 0) {
            $('#lista-empresas').addClass('hidden');
            return;
        }
        
        let html = '';
        empresas.slice(0, 10).forEach(function(empresa) { // Limitar a 10 resultados
            html += `
                <div class="empresa-item px-4 py-3 hover:bg-scordon-50 cursor-pointer border-b border-gray-100 last:border-b-0" 
                     data-id="${empresa.ID}" data-nome="${empresa.aNome}">
                    <div class="flex items-center">
                        <i class="fas fa-building text-scordon-500 mr-3"></i>
                        <div>
                            <div class="font-semibold text-gray-800">${empresa.aNome}</div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        if (empresas.length > 10) {
            html += `
                <div class="px-4 py-2 text-center text-sm text-gray-500 bg-gray-50">
                    <i class="fas fa-info-circle mr-1"></i>
                    Mostrando 10 de ${empresas.length} resultados. Continue digitando para refinar.
                </div>
            `;
        }
        
        $('#lista-empresas').html(html).removeClass('hidden');
        
        // Evento de clique nas empresas
        $('.empresa-item').off('click').on('click', function() {
            const id = $(this).data('id');
            const nome = $(this).data('nome');
            
            $('#busca-empresa').val(nome);
            $('#nIdEmpresa').val(id).trigger('change');
            $('#lista-empresas').addClass('hidden');
        });
    }

    function carregarAtendentes(idEmpresa) {
        $('#nIdAtendente').html('<option value="">Carregando...</option>').prop('disabled', true);
        
        $.get(`{{ url('admin/empresas') }}/${idEmpresa}/atendentes`, function(data) {
            let opcoes = '<option value="">Selecione um atendente...</option>';
            const usuarioLogadoId = {{ auth()->id() }};
            
            data.forEach(function(atendente) {
                const selected = (atendente.ID == usuarioLogadoId) ? 'selected' : '';
                opcoes += `<option value="${atendente.ID}" ${selected}>${atendente.aNome}</option>`;
            });
            
            $('#nIdAtendente').html(opcoes).prop('disabled', false);
            
            // Se usuário logado está na lista, habilitar botão automaticamente
            if (usuarioLogadoId && data.some(a => a.ID == usuarioLogadoId)) {
                $('#gerar-btn').prop('disabled', false);
            }
        }).fail(function() {
            $('#nIdAtendente').html('<option value="">Erro ao carregar atendentes</option>');
            alert('Erro ao carregar atendentes');
        });
    }

    function gerarLink() {
        const formData = {
            nIdEmpresa: $('#nIdEmpresa').val(),
            nIdAtendente: $('#nIdAtendente').val()
        };

        $('#gerar-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Gerando...');

        $.post('{{ route("admin.gerar-link") }}', formData, function(response) {
            if (response.sucesso) {
                $('#link-gerado').val(response.link);
                $('#resultado-empresa').text(response.empresa);
                $('#resultado-atendente').text(response.atendente);
                
                $('#info-card').hide();
                $('#resultado-card').show();
            }
        }).fail(function(xhr) {
            const erro = xhr.responseJSON?.erro || 'Erro ao gerar link';
            alert(erro);
        }).always(function() {
            $('#gerar-btn').prop('disabled', false).html('<i class="fas fa-magic me-2"></i>Gerar Link de Avaliação');
        });
    }

    function copiarParaClipboard() {
        const linkInput = $('#link-gerado')[0];
        linkInput.select();
        linkInput.setSelectionRange(0, 99999); // Para mobile

        try {
            document.execCommand('copy');
            
            // Feedback visual
            const textoOriginal = $('#copiar-btn').html();
            $('#copiar-btn').html('<i class="fas fa-check"></i>').removeClass('btn-outline-secondary').addClass('btn-success');
            
            setTimeout(function() {
                $('#copiar-btn').html(textoOriginal).removeClass('btn-success').addClass('btn-outline-secondary');
            }, 2000);
            
        } catch (err) {
            alert('Erro ao copiar. Por favor, copie manualmente.');
        }
    }

    function resetarForm() {
        $('#resultado-card').hide();
        $('#info-card').show();
        $('#gerar-form')[0].reset();
        $('#busca-empresa').val('');
        $('#nIdEmpresa').val('');
        $('#lista-empresas').addClass('hidden');
        $('#nIdAtendente').html('<option value="">Primeiro selecione uma empresa...</option>').prop('disabled', true);
        $('#gerar-btn').prop('disabled', true);
    }
});
</script>
</body>
</html>
