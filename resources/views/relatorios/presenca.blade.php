<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Presenças - ShepherdPath</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { text-align: center; color: #1d4ed8; margin-bottom: 5px; }
        .periodo { text-align: center; font-style: italic; margin-bottom: 20px; color: #666; }
        .catequista-title { background-color: #f3f4f6; padding: 8px; border-left: 4px solid #1d4ed8; margin-top: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f9fafb; font-weight: bold; }
        .empty-state { text-align: center; color: #999; margin-top: 50px; }
    </style>
</head>
<body>

    <h1>Relatório de Presença - Catequese</h1>
    <div class="periodo">Período: {{ $periodo }}</div>

    @forelse($agrupamento as $catequista => $listaPresencas)
        <div class="catequista-title">
            <strong>Catequista(s):</strong> {{ $catequista }}
        </div>
        
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="45%">Nome do Aluno(a)</th>
                    <th width="30%">Missa Frequentada</th>
                    <th width="20%">Data do Check-in</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listaPresencas as $index => $presenca)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ optional($presenca->aluno)->nome_completo ?? 'N/A' }}</td>
                        <td>{{ optional($presenca->missa)->descricao ?? 'N/A' }}</td>
                        <td>{{ $presenca->data_missa ? $presenca->data_missa->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <div class="empty-state">
            Nenhuma presença registrada neste período.
        </div>
    @endforelse

</body>
</html>
