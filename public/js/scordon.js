// JavaScript Global do Sistema SCORDON
$(document).ready(function() {
    
    // Inicializar tooltips globalmente
    initTooltips();
    
    // Inicializar sistema de rating se existir
    if ($('.rating-stars').length) {
        initRatingSystem();
    }
    
    // Inicializar formulários de filtro
    if ($('#filtro-empresa').length) {
        initFiltroEmpresa();
    }
    
    // Inicializar sistema de cópia
    if ($('.btn-copiar').length) {
        initSistemaCopia();
    }
    
    // Adicionar efeitos visuais
    addVisualEffects();
});

// Sistema de Rating por Estrelas
function initRatingSystem() {
    const textosNota = {
        1: 'Muito Insatisfeito',
        2: 'Insatisfeito', 
        3: 'Neutro',
        4: 'Satisfeito',
        5: 'Muito Satisfeito'
    };

    let notaAtual = parseInt($('#nota-input').val()) || 0;

    // Inicializar se já tem valor
    if (notaAtual > 0) {
        atualizarEstrelas(notaAtual);
    }

    // Hover effect
    $('.star').hover(function() {
        const nota = $(this).data('rating');
        atualizarEstrelas(nota);
    }, function() {
        atualizarEstrelas(notaAtual);
    });

    // Click handler
    $('.star').click(function() {
        notaAtual = $(this).data('rating');
        $('#nota-input').val(notaAtual);
        atualizarEstrelas(notaAtual);
        
        // Habilitar botão de envio se existir
        $('.btn-enviar').prop('disabled', false);
        
        // Feedback visual
        $(this).addClass('animate-pulse');
        setTimeout(() => {
            $(this).removeClass('animate-pulse');
        }, 300);
    });

    function atualizarEstrelas(nota) {
        $('.star').each(function() {
            const notaEstrela = $(this).data('rating');
            if (notaEstrela <= nota) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });

        // Atualizar texto da nota
        const textoElement = $('#rating-text');
        if (textoElement.length) {
            if (nota > 0) {
                textoElement.text(textosNota[nota]);
                textoElement.removeClass('text-gray-500').addClass('text-scordon-600 font-semibold');
            } else {
                textoElement.text('Clique nas estrelas para avaliar');
                textoElement.removeClass('text-scordon-600 font-semibold').addClass('text-gray-500');
            }
        }
    }
}

// Sistema de Filtro por Empresa
function initFiltroEmpresa() {
    $('#filtro-empresa, #nIdEmpresa').change(function() {
        const idEmpresa = $(this).val();
        const selectAtendentes = $('#filtro-atendente, #nIdAtendente');
        
        if (idEmpresa) {
            // Mostrar loading
            selectAtendentes.html('<option value="">Carregando...</option>').prop('disabled', true);
            
            // Buscar atendentes
            const url = `/admin/api/empresas/${idEmpresa}/atendentes`;
            
            $.get(url, function(data) {
                let opcoes = '<option value="">Todos os atendentes</option>';
                data.forEach(function(atendente) {
                    opcoes += `<option value="${atendente.ID}">${atendente.aNome}</option>`;
                });
                selectAtendentes.html(opcoes).prop('disabled', false);
            }).fail(function() {
                selectAtendentes.html('<option value="">Erro ao carregar</option>');
                mostrarNotificacao('Erro ao carregar atendentes', 'erro');
            });
        } else {
            selectAtendentes.html('<option value="">Todos os atendentes</option>').prop('disabled', false);
        }
    });
}

// Sistema de Cópia para Clipboard
function initSistemaCopia() {
    $('.btn-copiar').click(function() {
        const targetInput = $($(this).data('target') || '#link-input')[0];
        
        if (targetInput) {
            targetInput.select();
            targetInput.setSelectionRange(0, 99999); // Para mobile

            try {
                document.execCommand('copy');
                
                // Feedback visual
                const btn = $(this);
                const textoOriginal = btn.html();
                
                btn.html('<i class="fas fa-check mr-2"></i>Copiado!')
                   .removeClass('btn-outline-secondary')
                   .addClass('btn-success');
                
                setTimeout(function() {
                    btn.html(textoOriginal)
                       .removeClass('btn-success')
                       .addClass('btn-outline-secondary');
                }, 2000);
                
                mostrarNotificacao('Link copiado com sucesso!', 'sucesso');
                
            } catch (err) {
                mostrarNotificacao('Erro ao copiar. Copie manualmente.', 'erro');
            }
        }
    });
}

// Geração de Links
function gerarLinkAvaliacao(formData) {
    const btn = $('#btn-gerar');
    const textoOriginal = btn.html();
    
    // Mostrar loading
    btn.prop('disabled', true).html('<div class="spinner-scordon"></div>Gerando...');
    
    $.post('/admin/gerar-link', formData, function(response) {
        if (response.sucesso) {
            // Mostrar resultado
            $('#link-input').val(response.link);
            $('#resultado-empresa').text(response.empresa);
            $('#resultado-atendente').text(response.atendente);
            
            // Mostrar card de resultado
            $('#card-formulario').hide();
            $('#card-resultado').show();
            
            mostrarNotificacao('Link gerado com sucesso!', 'sucesso');
        }
    }).fail(function(xhr) {
        const erro = xhr.responseJSON?.erro || 'Erro ao gerar link';
        mostrarNotificacao(erro, 'erro');
    }).always(function() {
        btn.prop('disabled', false).html(textoOriginal);
    });
}

// Sistema de Notificações
function mostrarNotificacao(mensagem, tipo = 'info') {
    const cores = {
        'sucesso': 'bg-green-500',
        'erro': 'bg-red-500',
        'aviso': 'bg-yellow-500',
        'info': 'bg-blue-500'
    };
    
    const icones = {
        'sucesso': 'fas fa-check-circle',
        'erro': 'fas fa-exclamation-circle',
        'aviso': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    };
    
    const notificacao = $(`
        <div class="fixed top-4 right-4 ${cores[tipo]} text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300">
            <div class="flex items-center">
                <i class="${icones[tipo]} mr-3"></i>
                <span>${mensagem}</span>
                <button class="ml-4 hover:text-gray-200" onclick="$(this).parent().parent().remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `);
    
    $('body').append(notificacao);
    
    // Animar entrada
    setTimeout(() => {
        notificacao.removeClass('translate-x-full');
    }, 100);
    
    // Auto remover após 5 segundos
    setTimeout(() => {
        notificacao.addClass('translate-x-full');
        setTimeout(() => {
            notificacao.remove();
        }, 300);
    }, 5000);
}

// Inicializar Tooltips
function initTooltips() {
    $('[data-toggle="tooltip"], [data-bs-toggle="tooltip"]').each(function() {
        // Implementação simples de tooltip
        $(this).hover(function() {
            const title = $(this).attr('title') || $(this).data('title');
            if (title) {
                const tooltip = $(`<div class="tooltip-scordon">${title}</div>`);
                $('body').append(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.css({
                    position: 'fixed',
                    top: rect.top - tooltip.height() - 10,
                    left: rect.left + (rect.width / 2) - (tooltip.width() / 2),
                    background: 'var(--scordon-cinza)',
                    color: 'white',
                    padding: '8px 12px',
                    borderRadius: '8px',
                    fontSize: '14px',
                    zIndex: 1000,
                    whiteSpace: 'nowrap'
                });
            }
        }, function() {
            $('.tooltip-scordon').remove();
        });
    });
}

// Efeitos Visuais
function addVisualEffects() {
    // Efeito de hover nos cards
    $('.card').hover(function() {
        $(this).addClass('shadow-lg');
    }, function() {
        $(this).removeClass('shadow-lg');
    });
    
    // Efeito de loading nos botões
    $('.btn').click(function() {
        if (!$(this).hasClass('no-loading')) {
            $(this).addClass('loading');
        }
    });
    
    // Animação de entrada para elementos
    $('.fade-in').each(function(index) {
        $(this).css('opacity', '0').delay(index * 200).animate({opacity: 1}, 600);
    });
}

// Validação de Formulários
function validarFormulario(formId) {
    const form = $(formId);
    let valido = true;
    
    form.find('input[required], select[required], textarea[required]').each(function() {
        if (!$(this).val()) {
            $(this).addClass('border-red-500');
            valido = false;
        } else {
            $(this).removeClass('border-red-500');
        }
    });
    
    return valido;
}

// Contador de Caracteres
function initContadorCaracteres(textareaId, maxLength = 1000) {
    const textarea = $(textareaId);
    const contador = $(`<small class="text-gray-500 mt-1 block">${maxLength} caracteres restantes</small>`);
    textarea.after(contador);
    
    textarea.on('input', function() {
        const atual = $(this).val().length;
        const restante = maxLength - atual;
        
        contador.text(`${restante} caracteres restantes`);
        
        if (restante < 0) {
            contador.removeClass('text-gray-500').addClass('text-red-500');
            $(this).addClass('border-red-500');
        } else if (restante < 50) {
            contador.removeClass('text-gray-500 text-red-500').addClass('text-yellow-500');
            $(this).removeClass('border-red-500');
        } else {
            contador.removeClass('text-red-500 text-yellow-500').addClass('text-gray-500');
            $(this).removeClass('border-red-500');
        }
    });
}

// Exportar CSV
function exportarCSV(tabelaId, nomeArquivo = 'relatorio') {
    const tabela = document.getElementById(tabelaId);
    if (!tabela) return;
    
    const linhas = tabela.querySelectorAll('tr');
    let csvContent = "data:text/csv;charset=utf-8,";
    
    // Extrair cabeçalhos
    const cabecalhos = Array.from(linhas[0].querySelectorAll('th')).map(th => th.textContent.trim());
    csvContent += cabecalhos.join(',') + "\n";
    
    // Extrair dados
    for (let i = 1; i < linhas.length; i++) {
        const celulas = linhas[i].querySelectorAll('td');
        if (celulas.length > 0) {
            const linha = Array.from(celulas).map(td => {
                return `"${td.textContent.trim().replace(/"/g, '""')}"`;
            }).join(',');
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
    
    mostrarNotificacao('Arquivo CSV exportado com sucesso!', 'sucesso');
}

// Utilitários
const ScordonUtils = {
    // Formatar data brasileira
    formatarData: function(data) {
        return new Date(data).toLocaleDateString('pt-BR');
    },
    
    // Formatar data e hora brasileira
    formatarDataHora: function(data) {
        return new Date(data).toLocaleString('pt-BR');
    },
    
    // Debounce para pesquisas
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Validar email
    validarEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },
    
    // Limpar formulário
    limparFormulario: function(formId) {
        $(formId)[0].reset();
        $(formId).find('.border-red-500').removeClass('border-red-500');
        $(formId).find('.text-red-500').removeClass('text-red-500');
    }
};

// Configurações globais do AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Handler global para erros AJAX
$(document).ajaxError(function(event, xhr, settings, thrownError) {
    console.error('Erro AJAX:', thrownError);
    
    if (xhr.status === 419) {
        mostrarNotificacao('Sessão expirada. Recarregue a página.', 'erro');
    } else if (xhr.status === 500) {
        mostrarNotificacao('Erro interno do servidor. Tente novamente.', 'erro');
    } else if (xhr.status === 422) {
        const errors = xhr.responseJSON?.errors;
        if (errors) {
            Object.values(errors).forEach(errorArray => {
                errorArray.forEach(error => {
                    mostrarNotificacao(error, 'erro');
                });
            });
        }
    }
});

// Smooth scroll para links internos
$('a[href^="#"]').click(function(e) {
    e.preventDefault();
    const target = $($(this).attr('href'));
    if (target.length) {
        $('html, body').animate({
            scrollTop: target.offset().top - 100
        }, 800);
    }
});
