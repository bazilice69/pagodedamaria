@php
    $unidade = $c->tipo_meta === 'dinheiro' ? '' : ' '.$c->unidade_meta;
    $fmt = fn($v) => $c->tipo_meta === 'dinheiro'
        ? 'R$ '.number_format($v, 2, ',', '.')
        : rtrim(rtrim(number_format($v, 2, ',', '.'), '0'), ',').$unidade;
@endphp
<div class="bg-white rounded-xl2 shadow-sm overflow-hidden flex flex-col">
    @if ($c->foto)
        <img src="{{ asset('storage/'.$c->foto) }}" alt="{{ $c->titulo }}" class="h-40 w-full object-cover">
    @else
        <div class="h-40 w-full bg-gradient-to-br from-marca to-terracota flex items-center justify-center text-5xl">🎯</div>
    @endif
    <div class="p-5 flex flex-col flex-1">
        <h3 class="text-lg font-bold text-marca">{{ $c->titulo }}</h3>
        @if ($c->descricao)<p class="text-sm text-cafe/70 mt-1 line-clamp-2">{{ $c->descricao }}</p>@endif

        <div class="mt-4">
            <div class="flex justify-between text-sm font-semibold mb-1">
                <span>{{ $fmt($c->arrecadado) }}</span>
                <span class="text-terracota">{{ $c->progresso }}%</span>
            </div>
            <div class="barra-progresso"><span style="width: {{ $c->progresso }}%"></span></div>
            <p class="text-xs text-cafe/60 mt-1">Meta: {{ $fmt($c->meta) }}</p>
        </div>

        <a href="{{ route('campanhas.show', $c) }}" class="mt-4 text-center rounded-full bg-marca text-white text-sm font-semibold py-2 hover:brightness-110 transition">
            Ver campanha
        </a>
    </div>
</div>
