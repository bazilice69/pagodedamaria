<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $gerado->titulo }} — {{ $gerado->pessoa?->nome }}</title>
    <style>
        @media print { .no-print { display: none !important; } }
        body { font-family: Georgia, 'Times New Roman', serif; color: #222; max-width: 720px; margin: 24px auto; padding: 0 24px; line-height: 1.7; }
        .cabecalho { text-align: center; border-bottom: 2px solid #164A36; padding-bottom: 12px; margin-bottom: 24px; }
        .cabecalho h1 { color: #164A36; font-size: 20px; margin: 0; }
        .conteudo { white-space: pre-line; text-align: justify; }
        .assinatura { margin-top: 64px; text-align: center; }
        .linha { border-top: 1px solid #333; width: 320px; margin: 0 auto; padding-top: 6px; }
        .acoes { text-align: center; margin: 24px 0; }
        .btn { background: #164A36; color: #fff; border: none; border-radius: 999px; padding: 10px 24px; font-size: 14px; cursor: pointer; font-family: sans-serif; }
    </style>
</head>
<body>
    <div class="acoes no-print">
        <button class="btn" onclick="window.print()">🖨️ Imprimir / Salvar PDF</button>
    </div>

    <div class="cabecalho">
        @if (file_exists(public_path('img/logo.png')))<img src="{{ asset('img/logo.png') }}" style="height:56px" alt="">@endif
        <h1>{{ $assoc->nome ?? 'Pagode da Dona Maria' }}</h1>
        <div style="font-size:13px;color:#666">{{ $gerado->titulo }}</div>
    </div>

    <div class="conteudo">{{ $gerado->conteudo }}</div>

    <div class="assinatura">
        <div class="linha">{{ $gerado->pessoa?->responsavel?->nome ?: 'Assinatura do responsável' }}</div>
    </div>

    <p style="font-size:11px;color:#999;text-align:center;margin-top:40px">
        Documento gerado em {{ $gerado->created_at->format('d/m/Y') }} · {{ $assoc->nome ?? 'Pagode da Dona Maria' }}
    </p>
</body>
</html>
