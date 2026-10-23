@php
    $cores = [
        'urgente' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => '🔴 URGENTE'],
        'alta'    => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => '🟠 Prioridade alta'],
        'media'   => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => '🟡 Prioridade média'],
        'baixa'   => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => '⚪ Prioridade baixa'],
    ][$n->prioridade] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => ''];

    $texto = rawurlencode(
        "🙏 O ".($assoc->nome ?? 'Pagode da Dona Maria')." está precisando de {$n->titulo}.\n".
        "Meta: ".rtrim(rtrim(number_format($n->meta,2,',','.'),'0'),',')." {$n->unidade}. ".
        "Faltam ".rtrim(rtrim(number_format($n->restante,2,',','.'),'0'),',')." {$n->unidade}.\n".
        "💜 Ajude você também! ".route('necessidades.index')
    );
@endphp
<div class="bg-white rounded-xl2 shadow-sm p-5">
    <div class="flex items-center justify-between">
        <span class="text-xs font-bold px-3 py-1 rounded-full {{ $cores['bg'] }} {{ $cores['text'] }}">{{ $cores['label'] }}</span>
        @if ($n->prazo)<span class="text-xs text-cafe/50">até {{ $n->prazo->format('d/m') }}</span>@endif
    </div>
    <h3 class="mt-3 text-lg font-bold text-marca">{{ $n->titulo }}</h3>
    @if ($n->descricao)<p class="text-sm text-cafe/70 mt-1">{{ $n->descricao }}</p>@endif

    <div class="mt-4">
        <div class="flex justify-between text-sm font-semibold mb-1">
            <span>{{ rtrim(rtrim(number_format($n->arrecadado,2,',','.'),'0'),',') }} de {{ rtrim(rtrim(number_format($n->meta,2,',','.'),'0'),',') }} {{ $n->unidade }}</span>
            <span class="text-terracota">{{ $n->progresso }}%</span>
        </div>
        <div class="barra-progresso"><span style="width: {{ $n->progresso }}%"></span></div>
        @if ($n->restante > 0)
            <p class="text-xs text-cafe/60 mt-1">Faltam {{ rtrim(rtrim(number_format($n->restante,2,',','.'),'0'),',') }} {{ $n->unidade }}</p>
        @endif
    </div>

    <div class="mt-4 flex gap-2">
        <a href="{{ route('como-ajudar') }}" class="flex-1 text-center rounded-full bg-terracota text-white text-sm font-semibold py-2 hover:brightness-95 transition">Quero ajudar</a>
        <a target="_blank" href="https://wa.me/?text={{ $texto }}" class="rounded-full bg-green-500 text-white text-sm font-semibold px-4 py-2 hover:brightness-95 transition" title="Compartilhar no WhatsApp">📲</a>
    </div>
</div>
