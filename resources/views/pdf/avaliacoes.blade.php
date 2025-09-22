<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Avaliações - SCORDON</title>
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
        
        .nota {
            font-weight: bold;
            text-align: center;
        }
        
        .nota-5 { color: #059669; }
        .nota-4 { color: #10b981; }
        .nota-3 { color: #f59e0b; }
        .nota-2 { color: #f97316; }
        .nota-1 { color: #dc2626; }
        
        .data {
            text-align: center;
            white-space: nowrap;
        }
        
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
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">SCORDON Sistema de Avaliação</div>
        <div class="title">Relatório de Avaliações</div>
        <div class="subtitle">Análise detalhada das avaliações recebidas</div>
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

    <div class="stats">
        <div class="stat-card">
            <div class="stat-number">{{ $totalAvaliacoes }}</div>
            <div class="stat-label">Total de Avaliações</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $notaMedia ? number_format($notaMedia, 1) : '0.0' }}</div>
            <div class="stat-label">Nota Média</div>
        </div>
    </div>

    <div class="table-container">
        @if($avaliacoes->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 20%;">Empresa</th>
                    <th style="width: 20%;">Atendente</th>
                    <th style="width: 15%;">Gerado Por</th>
                    <th style="width: 8%;">Nota</th>
                    <th style="width: 15%;">Comentário</th>
                    <th style="width: 14%;">Data Avaliação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($avaliacoes as $index => $avaliacao)
                <tr>
                    <td class="data">{{ $index + 1 }}</td>
                    <td>{{ $avaliacao->empresa->aNome ?? 'N/A' }}</td>
                    <td>{{ $avaliacao->atendente->aNome ?? 'N/A' }}</td>
                    <td>{{ $avaliacao->usuarioGerador->aNome ?? 'Sistema/Admin' }}</td>
                    <td class="nota nota-{{ $avaliacao->nNota }}">
                        {{ $avaliacao->nNota }}
                    </td>
                    <td style="max-width: 200px; word-wrap: break-word;">
                        {{ Str::limit($avaliacao->aComentario, 50) }}
                    </td>
                    <td class="data">
                        {{ $avaliacao->dAvaliadoEm ? \Carbon\Carbon::parse($avaliacao->dAvaliadoEm)->format('d/m/Y H:i') : 'N/A' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">
            Nenhuma avaliação encontrada com os filtros aplicados.
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Relatório gerado em {{ now()->format('d/m/Y H:i:s') }} | SCORDON Sistema de Avaliação</p>
    </div>
</body>
</html>
