<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Catequizandos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { text-align: center; color: #1d4ed8; margin-bottom: 5px; }
        .periodo { text-align: center; font-style: italic; margin-bottom: 20px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f9fafb; font-weight: bold; color: #111; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>

    <h1>Relatório de Catequizandos</h1>
    <div class="periodo">Gerado em: {{ now()->format('d/m/Y H:i:s') }}</div>
    
    <table>
        <thead>
            <tr>
                <th width="30%">Nome</th>
                <th width="10%">Idade</th>
                <th width="20%">Etapa</th>
                <th width="20%">Catequista</th>
                <th width="20%">Responsável</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $catequizando)
                <tr>
                    <td>{{ $catequizando->nome_completo }}</td>
                    <td>{{ $catequizando->idade ?? '-' }}</td>
                    <td>{{ optional($catequizando->etapa)->nome ?? '-' }}</td>
                    <td>{{ optional($catequizando->catequista)->nomes ?? '-' }}</td>
                    <td>{{ $catequizando->nome_responsavel ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Documento gerado pelo sistema ShepherdPath
    </div>

</body>
</html>
