<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Produtividade - SCORDON</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #f97316;
            padding-bottom: 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #f97316;
            margin-bottom: 10px;
        }
        
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 14px;
            color: #6b7280;
        }
        
        .filters {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #f97316;
        }
        
        .filters h3 {
            margin: 0 0 10px 0;
            color: #1f2937;
            font-size: 14px;
        }
        
        .filter-row {
            display: flex;
            gap: 30px;
            margin-bottom: 5px;
        }
        
        .filter-item {
            flex: 1;
        }
        
        .filter-label {
            font-weight: bold;
            color: #374151;
        }
        
        .filter-value {
            color: #6b7280;
        }
        
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background-color: #f97316;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            flex: 1;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .table-container {
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background-color: #f97316;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .rank {
            text-align: center;
            font-weight: bold;
            color: #f97316;
        }
        
        .performance {
            text-align: center;
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 10px;
        }
        
        .performance.excelente {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .performance.bom {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .performance.regular {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .performance.baixo {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .taxa-conversao {
            text-align: center;
            font-weight: bold;
        }
        
        .taxa-alta { color: #059669; }
        .taxa-media { color: #f59e0b; }
        .taxa-baixa { color: #dc2626; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            font-style: italic;
        }
        
        .summary {
            background-color: #f0f9ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #0ea5e9;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            color: #0c4a6e;
            font-size: 14px;
        }
        
        .summary-stats {
            display: flex;
            gap: 30px;
        }
        
        .summary-item {
            flex: 1;
        }
        
        .summary-label {
            font-weight: bold;
            color: #0c4a6e;
            font-size: 11px;
        }
        
        .summary-value {
            color: #0369a1;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">SCORDON Sistema de Avaliação</div>
        <div class="title">Relatório de Produtividade</div>
        <div class="subtitle">Análise de performance dos usuários</div>
    </div>

    @if($empresa || $atendente || $dataInicio || $dataFim)
    <div class="filters">
        <h3>Filtros Aplicados</h3>
        <div class="filter-row">
            @if($empresa)
            <div class="filter-item">
                <span class="filter-label">Empresa:</span>
                <span class="filter-value">{{ $empresa->aNome }}</span>
            </div>
            @endif
            @if($atendente)
            <div class="filter-item">
                <span class="filter-label">Atendente:</span>
                <span class="filter-value">{{ $atendente->aNome }}</span>
            </div>
            @endif
        </div>
        <div class="filter-row">
            @if($dataInicio)
            <div class="filter-item">
                <span class="filter-label">Data Início:</span>
                <span class="filter-value">{{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }}</span>
            </div>
            @endif
            @if($dataFim)
            <div class="filter-item">
                <span class="filter-label">Data Fim:</span>
                <span class="filter-value">{{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    @php
        $totalLinks = collect($relatorioProdutividade)->sum('total_links_gerados');
        $totalAvaliacoes = collect($relatorioProdutividade)->sum('total_avaliacoes');
        $taxaMedia = $totalLinks > 0 ? round(($totalAvaliacoes / $totalLinks) * 100, 1) : 0;
        $totalUsuarios = count($relatorioProdutividade);
    @endphp

    <div class="summary">
        <h3>Resumo Geral</h3>
        <div class="summary-stats">
            <div class="summary-item">
                <div class="summary-label">Total de Usuários</div>
                <div class="summary-value">{{ $totalUsuarios }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total de Links Gerados</div>
                <div class="summary-value">{{ $totalLinks }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total de Avaliações</div>
                <div class="summary-value">{{ $totalAvaliacoes }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Taxa Média de Conversão</div>
                <div class="summary-value">{{ $taxaMedia }}%</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        @if(count($relatorioProdutividade) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Rank</th>
                    <th style="width: 25%;">Usuário</th>
                    <th style="width: 15%;">Links Gerados</th>
                    <th style="width: 15%;">Avaliações Recebidas</th>
                    <th style="width: 15%;">Taxa de Conversão</th>
                    <th style="width: 22%;">Performance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($relatorioProdutividade as $index => $item)
                @php
                    $performance = 'baixo';
                    $performanceClass = 'baixo';
                    $performanceText = 'Baixo';
                    
                    if ($item['taxa_conversao'] >= 80) {
                        $performance = 'excelente';
                        $performanceClass = 'excelente';
                        $performanceText = '★ Excelente';
                    } elseif ($item['taxa_conversao'] >= 60) {
                        $performance = 'bom';
                        $performanceClass = 'bom';
                        $performanceText = '▲ Bom';
                    } elseif ($item['taxa_conversao'] >= 40) {
                        $performance = 'regular';
                        $performanceClass = 'regular';
                        $performanceText = '▲ Regular';
                    }
                    
                    $taxaClass = 'taxa-baixa';
                    if ($item['taxa_conversao'] >= 70) {
                        $taxaClass = 'taxa-alta';
                    } elseif ($item['taxa_conversao'] >= 40) {
                        $taxaClass = 'taxa-media';
                    }
                @endphp
                <tr>
                    <td class="rank">{{ $index + 1 }}º</td>
                    <td>{{ $item['usuario'] }}</td>
                    <td style="text-align: center;">{{ $item['total_links_gerados'] }}</td>
                    <td style="text-align: center;">{{ $item['total_avaliacoes'] }}</td>
                    <td class="taxa-conversao {{ $taxaClass }}">
                        {{ $item['taxa_conversao'] }}%
                    </td>
                    <td>
                        <span class="performance {{ $performanceClass }}">
                            {{ $performanceText }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">
            Nenhum dado de produtividade encontrado com os filtros aplicados.
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Relatório gerado em {{ now()->format('d/m/Y H:i:s') }} | SCORDON Sistema de Avaliação</p>
        <p>Classificação: ★ Excelente (≥80%) | ▲ Bom (60-79%) | ▲ Regular (40-59%) | Baixo (<40%)</p>
    </div>
</body>
</html>
