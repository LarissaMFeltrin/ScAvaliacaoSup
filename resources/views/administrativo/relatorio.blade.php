<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relatório de Avaliações - SCORDON</title>
    
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
                    <a href="{{ route('admin.relatorio') }}" class="hover:text-scordon-100 transition-colors font-semibold">
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
            
            <!-- Header do Relatório -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-file-alt text-scordon-500 mr-3"></i>
                    Relatório de Avaliações
                </h1>
                <p class="text-xl text-gray-600 mb-6">Análise detalhada das avaliações recebidas</p>
                <a href="{{ route('admin.dashboard') }}" class="btn-verde">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Ver Dashboard
                </a>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-filter text-scordon-500 mr-2"></i>
                    Filtros de Pesquisa
                </h3>
                <form method="GET" action="{{ route('admin.relatorio') }}" class="grid md:grid-cols-5 gap-4">
                    <div>
                        <label for="nIdEmpresa" class="block text-sm font-semibold text-gray-700 mb-2">Empresa</label>
                        <div class="relative">
                            <input type="text" 
                                   id="buscaEmpresa" 
                                   class="w-full form-control-scordon pr-10" 
                                   placeholder="Clique para ver empresas ou digite para buscar..."
                                   autocomplete="off">
                            <input type="hidden" name="nIdEmpresa" id="nIdEmpresa" value="{{ $idEmpresa }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <div id="sugestoesEmpresas" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto" style="z-index: 9999;">
                                <!-- Sugestões serão carregadas aqui via JavaScript -->
                            </div>
                        </div>
                        <div id="empresaSelecionada" class="mt-2 hidden">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-scordon-100 text-scordon-800">
                                <span id="nomeEmpresaSelecionada"></span>
                                <button type="button" id="removerEmpresa" class="ml-2 text-scordon-600 hover:text-scordon-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label for="nIdAtendente" class="block text-sm font-semibold text-gray-700 mb-2">Atendente</label>
                        <div class="relative">
                            <input type="text" 
                                   id="buscaAtendente" 
                                   class="w-full form-control-scordon pr-10" 
                                   placeholder="Clique para ver atendentes ou digite para buscar..."
                                   autocomplete="off">
                            <input type="hidden" name="nIdAtendente" id="nIdAtendente" value="{{ $idAtendente }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <div id="sugestoesAtendentes" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto" style="z-index: 9999;">
                                <!-- Sugestões serão carregadas aqui via JavaScript -->
                            </div>
                        </div>
                        <div id="atendenteSelecionado" class="mt-2 hidden">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-scordon-100 text-scordon-800">
                                <span id="nomeAtendenteSelecionado"></span>
                                <button type="button" id="removerAtendente" class="ml-2 text-scordon-600 hover:text-scordon-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        </div>
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

            <!-- Resumo dos Resultados -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-list text-scordon-500 mr-2"></i>
                            Lista de Avaliações
                        </h3>
                        <p class="text-gray-600">{{ $avaliacoes->total() }} registros encontrados</p>
                    </div>
                    
                    @if($avaliacoes->count() > 0)
                        <button onclick="exportarCSV('tabela-avaliacoes', 'relatorio-avaliacoes')" class="btn-verde mt-4 md:mt-0">
                            <i class="fas fa-download mr-2"></i>
                            Exportar CSV
                        </button>
                    @endif
                </div>
            </div>

            <!-- Tabela de Avaliações -->
            @if($avaliacoes->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                    <div class="overflow-x-auto">
                        <table class="w-full table-scordon" id="tabela-avaliacoes">
                            <thead class="gradient-bg text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold">Data/Hora</th>
                                    <th class="px-6 py-4 text-left font-semibold">Empresa</th>
                                    <th class="px-6 py-4 text-left font-semibold">Atendente</th>
                                    <th class="px-6 py-4 text-left font-semibold">Cliente</th>
                                    <th class="px-6 py-4 text-center font-semibold">Avaliação</th>
                                    <th class="px-6 py-4 text-left font-semibold">Comentário</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($avaliacoes as $avaliacao)
                                    <tr class="hover:bg-scordon-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-800">
                                                {{ $avaliacao->dAvaliadoEm->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $avaliacao->dAvaliadoEm->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-800">
                                                {{ $avaliacao->empresa->aNome }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-700">
                                                {{ $avaliacao->atendente->aNome }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($avaliacao->aNomeCliente)
                                                <div class="font-semibold text-gray-800">{{ $avaliacao->aNomeCliente }}</div>
                                                @if($avaliacao->aEmailCliente)
                                                    <div class="text-xs text-gray-500">{{ $avaliacao->aEmailCliente }}</div>
                                                @endif
                                            @else
                                                <span class="text-gray-400 italic">Anônimo</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center space-x-1 mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $avaliacao->nNota)
                                                        <i class="fas fa-star text-scordon-500"></i>
                                                    @else
                                                        <i class="far fa-star text-gray-300"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                                @if($avaliacao->nNota >= 4) bg-green-100 text-green-800
                                                @elseif($avaliacao->nNota == 3) bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $avaliacao->texto_nota }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($avaliacao->aComentario)
                                                <div class="max-w-xs">
                                                    <div class="text-sm text-gray-700 cursor-pointer" 
                                                         data-bs-toggle="tooltip" 
                                                         title="{{ $avaliacao->aComentario }}">
                                                        {{ Str::limit($avaliacao->aComentario, 80) }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">Sem comentário</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginação -->
                    @if($avaliacoes->hasPages())
                        <div class="bg-gray-50 px-6 py-4 border-t">
                            <!-- Informações da Paginação -->
                            <div class="pagination-info mb-4">
                                <i class="fas fa-info-circle text-scordon-500 mr-2"></i>
                                Mostrando {{ $avaliacoes->firstItem() }} até {{ $avaliacoes->lastItem() }} de {{ $avaliacoes->total() }} resultados
                            </div>
                            <!-- Links de Paginação -->
                            <div class="flex justify-center">
                                {{ $avaliacoes->withQueryString()->links('custom.pagination') }}
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <!-- Estado Vazio -->
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                    <div class="mb-6">
                        <i class="fas fa-inbox text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Nenhuma avaliação encontrada</h3>
                    <p class="text-gray-600 mb-8">
                        Tente ajustar os filtros ou aguarde novas avaliações serem realizadas.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('admin.index') }}" class="btn-scordon">
                            <i class="fas fa-plus mr-2"></i>
                            Gerar Novo Link
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn-verde">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Ver Dashboard
                        </a>
                    </div>
                </div>
            @endif
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
    
    <!-- Estilos para busca de empresas e atendentes -->
    <style>
        .sugestao-empresa, .sugestao-atendente {
            transition: background-color 0.2s ease;
        }
        
        .sugestao-empresa:hover, .sugestao-atendente:hover {
            background-color: #f3f4f6 !important;
        }
        
        #sugestoesEmpresas, #sugestoesAtendentes {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        #empresaSelecionada, #atendenteSelecionado {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    <script>
    $(document).ready(function() {
        // Inicializar busca de empresas
        initBuscaEmpresas();
        
        // Inicializar busca de atendentes
        initBuscaAtendentes();
        
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

        // Inicializar tooltips
        initTooltips();
    });

    // Função para exportar CSV customizada para esta página
    function exportarCSV(tabelaId, nomeArquivo) {
        const tabela = document.getElementById(tabelaId);
        if (!tabela) return;
        
        const linhas = tabela.querySelectorAll('tr');
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Data,Hora,Empresa,Atendente,Cliente,Email,Nota,Comentario\n";
        
        // Extrair dados (pular cabeçalho)
        for (let i = 1; i < linhas.length; i++) {
            const celulas = linhas[i].querySelectorAll('td');
            if (celulas.length > 0) {
                const dataHora = celulas[0].textContent.trim().split('\n');
                const data = dataHora[0].trim();
                const hora = dataHora[1] ? dataHora[1].trim() : '';
                const empresa = celulas[1].textContent.trim();
                const atendente = celulas[2].textContent.trim();
                
                // Extrair dados do cliente
                const clienteTexto = celulas[3].textContent.trim();
                const nomeCliente = clienteTexto === 'Anônimo' ? '' : clienteTexto.split('\n')[0];
                const emailCliente = clienteTexto.includes('@') ? clienteTexto.split('\n')[1] || '' : '';
                
                // Extrair nota (contar estrelas ativas)
                const estrelas = celulas[4].querySelectorAll('.fas.fa-star');
                const nota = estrelas.length;
                
                const comentario = celulas[5].textContent.trim().replace('Sem comentário', '');
                
                const linha = [data, hora, empresa, atendente, nomeCliente, emailCliente, nota, comentario]
                    .map(campo => `"${campo.replace(/"/g, '""')}"`)
                    .join(',');
                
                csvContent += linha + "\n";
            }
        }
        
        // Download
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `${nomeArquivo}_${new Date().toISOString().split('T')[0]}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        mostrarNotificacao('Relatório CSV exportado com sucesso!', 'sucesso');
    }

    // Função para inicializar busca de empresas
    function initBuscaEmpresas() {
        const buscaInput = $('#buscaEmpresa');
        const sugestoesDiv = $('#sugestoesEmpresas');
        const empresaSelecionadaDiv = $('#empresaSelecionada');
        const nomeEmpresaSpan = $('#nomeEmpresaSelecionada');
        const idEmpresaInput = $('#nIdEmpresa');
        const removerEmpresaBtn = $('#removerEmpresa');
        
        let timeoutId;
        
        // Carregar empresa selecionada inicialmente se existir
        @if($idEmpresa)
            @foreach($empresas as $empresa)
                @if($empresa->ID == $idEmpresa)
                    mostrarEmpresaSelecionada('{{ $empresa->ID }}', '{{ $empresa->aNome }}');
                @endif
            @endforeach
        @endif
        
        // Evento de foco - mostrar primeiras 5 empresas
        buscaInput.on('focus', function() {
            if (sugestoesDiv.children().length === 0) {
                carregarEmpresasIniciais();
            } else {
                sugestoesDiv.show();
            }
        });
        
        // Evento de digitação com debounce
        buscaInput.on('input', function() {
            const termo = $(this).val().trim();
            
            clearTimeout(timeoutId);
            
            if (termo.length === 0) {
                // Se campo vazio, mostrar empresas iniciais
                carregarEmpresasIniciais();
                return;
            }
            
            if (termo.length < 2) {
                sugestoesDiv.hide();
                return;
            }
            
            timeoutId = setTimeout(() => {
                buscarEmpresas(termo);
            }, 300);
        });
        
        // Fechar sugestões ao clicar fora
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#buscaEmpresa, #sugestoesEmpresas').length) {
                sugestoesDiv.hide();
            }
        });
        
        // Evento para remover empresa selecionada
        removerEmpresaBtn.on('click', function() {
            limparSelecaoEmpresa();
        });
        
        function carregarEmpresasIniciais() {
            $.get('/admin/api/empresas/buscar', { q: '', limit: 5 })
                .done(function(data) {
                    mostrarSugestoesEmpresas(data, true);
                })
                .fail(function() {
                    console.error('Erro ao carregar empresas iniciais');
                });
        }
        
        function buscarEmpresas(termo) {
            $.get('/admin/api/empresas/buscar', { q: termo })
                .done(function(data) {
                    mostrarSugestoesEmpresas(data);
                })
                .fail(function() {
                    console.error('Erro ao buscar empresas');
                });
        }
        
        function mostrarSugestoesEmpresas(empresas, isInicial = false) {
            if (empresas.length === 0) {
                const mensagem = isInicial ? 'Nenhuma empresa cadastrada' : 'Nenhuma empresa encontrada';
                sugestoesDiv.html(`<div class="px-4 py-2 text-gray-500 text-sm">${mensagem}</div>`).show();
                return;
            }
            
            let html = '';
            
            // Adicionar cabeçalho se for busca inicial
            if (isInicial) {
                html += '<div class="px-4 py-2 text-xs text-gray-500 bg-gray-50 border-b">Primeiras empresas (digite para buscar mais):</div>';
            }
            
            empresas.forEach(function(empresa) {
                html += `
                    <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer sugestao-empresa" 
                         data-id="${empresa.ID}" 
                         data-nome="${empresa.aNome}">
                        <i class="fas fa-building text-gray-400 mr-2"></i>
                        ${empresa.aNome}
                    </div>
                `;
            });
            
            sugestoesDiv.html(html).show();
            
            // Evento de clique nas sugestões
            $('.sugestao-empresa').on('click', function() {
                const id = $(this).data('id');
                const nome = $(this).data('nome');
                
                selecionarEmpresa(id, nome);
            });
        }
        
        function selecionarEmpresa(id, nome) {
            idEmpresaInput.val(id);
            mostrarEmpresaSelecionada(id, nome);
            buscaInput.val('');
            sugestoesDiv.hide();
            
            // Disparar evento de mudança para atualizar atendentes
            idEmpresaInput.trigger('change');
        }
        
        function mostrarEmpresaSelecionada(id, nome) {
            nomeEmpresaSpan.text(nome);
            empresaSelecionadaDiv.show();
            buscaInput.hide();
        }
        
        function limparSelecaoEmpresa() {
            idEmpresaInput.val('');
            empresaSelecionadaDiv.hide();
            buscaInput.show().val('');
            sugestoesDiv.hide();
            
            // Disparar evento de mudança para atualizar atendentes
            idEmpresaInput.trigger('change');
        }
    }

    // Função para inicializar busca de atendentes
    function initBuscaAtendentes() {
        const buscaInput = $('#buscaAtendente');
        const sugestoesDiv = $('#sugestoesAtendentes');
        const atendenteSelecionadoDiv = $('#atendenteSelecionado');
        const nomeAtendenteSpan = $('#nomeAtendenteSelecionado');
        const idAtendenteInput = $('#nIdAtendente');
        const removerAtendenteBtn = $('#removerAtendente');
        
        let timeoutId;
        
        // Carregar atendente selecionado inicialmente se existir
        @if($idAtendente)
            @foreach($atendentes as $atendente)
                @if($atendente->ID == $idAtendente)
                    mostrarAtendenteSelecionado('{{ $atendente->ID }}', '{{ $atendente->aNome }}');
                @endif
            @endforeach
        @endif
        
        // Evento de foco - mostrar primeiros 5 atendentes
        buscaInput.on('focus', function() {
            if (sugestoesDiv.children().length === 0) {
                carregarAtendentesIniciais();
            } else {
                sugestoesDiv.show();
            }
        });
        
        // Evento de digitação com debounce
        buscaInput.on('input', function() {
            const termo = $(this).val().trim();
            
            clearTimeout(timeoutId);
            
            if (termo.length === 0) {
                // Se campo vazio, mostrar atendentes iniciais
                carregarAtendentesIniciais();
                return;
            }
            
            if (termo.length < 2) {
                sugestoesDiv.hide();
                return;
            }
            
            timeoutId = setTimeout(() => {
                buscarAtendentes(termo);
            }, 300);
        });
        
        // Fechar sugestões ao clicar fora
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#buscaAtendente, #sugestoesAtendentes').length) {
                sugestoesDiv.hide();
            }
        });
        
        // Evento para remover atendente selecionado
        removerAtendenteBtn.on('click', function() {
            limparSelecaoAtendente();
        });
        
        function carregarAtendentesIniciais() {
            $.get('/admin/api/atendentes/buscar', { q: '', limit: 5 })
                .done(function(data) {
                    mostrarSugestoesAtendentes(data, true);
                })
                .fail(function() {
                    console.error('Erro ao carregar atendentes iniciais');
                });
        }
        
        function buscarAtendentes(termo) {
            $.get('/admin/api/atendentes/buscar', { q: termo })
                .done(function(data) {
                    mostrarSugestoesAtendentes(data);
                })
                .fail(function() {
                    console.error('Erro ao buscar atendentes');
                });
        }
        
        function mostrarSugestoesAtendentes(atendentes, isInicial = false) {
            if (atendentes.length === 0) {
                const mensagem = isInicial ? 'Nenhum atendente cadastrado' : 'Nenhum atendente encontrado';
                sugestoesDiv.html(`<div class="px-4 py-2 text-gray-500 text-sm">${mensagem}</div>`).show();
                return;
            }
            
            let html = '';
            
            // Adicionar cabeçalho se for busca inicial
            if (isInicial) {
                html += '<div class="px-4 py-2 text-xs text-gray-500 bg-gray-50 border-b">Primeiros atendentes (digite para buscar mais):</div>';
            }
            
            atendentes.forEach(function(atendente) {
                html += `
                    <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer sugestao-atendente" 
                         data-id="${atendente.ID}" 
                         data-nome="${atendente.aNome}">
                        <i class="fas fa-user text-gray-400 mr-2"></i>
                        ${atendente.aNome}
                    </div>
                `;
            });
            
            sugestoesDiv.html(html).show();
            
            // Evento de clique nas sugestões
            $('.sugestao-atendente').on('click', function() {
                const id = $(this).data('id');
                const nome = $(this).data('nome');
                
                selecionarAtendente(id, nome);
            });
        }
        
        function selecionarAtendente(id, nome) {
            idAtendenteInput.val(id);
            mostrarAtendenteSelecionado(id, nome);
            buscaInput.val('');
            sugestoesDiv.hide();
        }
        
        function mostrarAtendenteSelecionado(id, nome) {
            nomeAtendenteSpan.text(nome);
            atendenteSelecionadoDiv.show();
            buscaInput.hide();
        }
        
        function limparSelecaoAtendente() {
            idAtendenteInput.val('');
            atendenteSelecionadoDiv.hide();
            buscaInput.show().val('');
            sugestoesDiv.hide();
        }
    }
    </script>
</body>
</html>