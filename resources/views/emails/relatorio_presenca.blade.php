<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; line-height: 1.6; }
        .header { border-bottom: 2px solid #1d4ed8; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { color: #1d4ed8; margin: 0; }
        .periodo { color: #666; font-style: italic; margin-top: 4px; }
        .mensagem { background: #f9fafb; border-left: 4px solid #1d4ed8; padding: 12px 16px; border-radius: 4px; }
        .footer { margin-top: 30px; font-size: 12px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>📋 Relatório de Presença – ShepherdPath</h2>
        <div class="periodo">Período: {{ $periodo }}</div>
    </div>

    <div class="mensagem">
        {!! nl2br(e($mensagem)) !!}
    </div>

    <p>O relatório completo de presenças está anexado a este e-mail em formato PDF.</p>

    <div class="footer">
        Este e-mail foi enviado automaticamente pelo sistema ShepherdPath.
    </div>
</body>
</html>
