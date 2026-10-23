<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carteirinha — {{ $pessoa->nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{marca:'#164A36',terracota:'#C96F4A',creme:'#F4E6D2',manteiga:'#F2C94C',cafe:'#39251F'}}}}</script>
    <style>
        @media print { .no-print { display: none !important; } body { background: #fff; } }
        body { font-family: ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-creme min-h-screen flex flex-col items-center justify-center p-6 gap-6">

    {{-- CARTEIRINHA (padrao cartao) --}}
    <div class="w-[340px] rounded-2xl overflow-hidden shadow-xl bg-white">
        {{-- Cabecalho --}}
        <div class="bg-marca text-white px-4 py-3 flex items-center gap-2">
            @if (file_exists(public_path('img/logo.png')))
                <span class="h-8 w-8 rounded-full bg-creme p-0.5 flex items-center justify-center shrink-0">
                    <img src="{{ asset('img/logo.png') }}" class="h-full w-full object-contain" alt="">
                </span>
            @endif
            <div class="leading-tight">
                <div class="font-extrabold text-sm">{{ $assoc->nome ?? 'Pagode da Dona Maria' }}</div>
                <div class="text-[10px] text-white/70">Carteira de Identificação</div>
            </div>
        </div>

        {{-- Corpo --}}
        <div class="p-4 flex gap-4">
            @if ($pessoa->foto && $pessoa->autoriza_imagem)
                <img src="{{ asset('storage/'.$pessoa->foto) }}" class="h-24 w-20 rounded-lg object-cover border-2 border-creme shrink-0">
            @else
                <span class="h-24 w-20 rounded-lg bg-creme flex items-center justify-center text-3xl shrink-0">👤</span>
            @endif
            <div class="flex-1 min-w-0">
                <div class="font-extrabold text-marca leading-tight">{{ $pessoa->nome }}</div>
                @if ($pessoa->apelido)<div class="text-xs text-terracota font-semibold">"{{ $pessoa->apelido }}"</div>@endif
                <div class="mt-2 text-xs text-cafe/70 space-y-0.5">
                    <div><strong>ID:</strong> {{ $pessoa->codigo }}</div>
                    @if ($pessoa->idade !== null)<div><strong>Idade:</strong> {{ $pessoa->idade }} anos</div>@endif
                    <div class="flex flex-wrap gap-1 pt-1">
                        @foreach ($pessoa->papeis as $papelLabel)<span class="text-[10px] bg-creme px-1.5 py-0.5 rounded">{{ $papelLabel }}</span>@endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- QR Code --}}
        <div class="px-4 pb-4 flex items-center gap-3 border-t border-creme pt-3">
            <img src="data:image/svg+xml;base64,{{ $qrSvg }}" class="h-20 w-20" alt="QR Code">
            <div class="text-[10px] text-cafe/60 leading-tight">
                Escaneie para validar.<br>
                <span class="text-marca font-semibold">{{ $assoc->whatsapp ?? '' }}</span>
            </div>
        </div>
    </div>

    {{-- Acoes (nao imprime) --}}
    <div class="no-print flex gap-3">
        <button onclick="window.print()" class="rounded-full bg-marca text-white font-semibold px-6 py-2.5">🖨️ Imprimir</button>
        <a href="{{ route('admin.pessoas.show', $pessoa) }}" class="rounded-full bg-white text-cafe font-semibold px-6 py-2.5 shadow">← voltar</a>
    </div>
    <p class="no-print text-xs text-cafe/50 max-w-[340px] text-center">
        Dica: na janela de impressão, escolha "Salvar como PDF" para enviar pelo WhatsApp.
    </p>
</body>
</html>
