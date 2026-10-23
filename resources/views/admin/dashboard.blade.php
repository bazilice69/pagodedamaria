@extends('layouts.admin')
@section('titulo', 'Painel')

@section('conteudo')
<h1 class="text-2xl md:text-3xl font-extrabold text-marca">Painel da Associação</h1>
<p class="text-cafe/60 mt-1">Resumo do mês de {{ now()->translatedFormat('F \d\e Y') }}</p>

{{-- Cartoes de resumo --}}
<div class="mt-6 grid grid-cols-2 lg:grid-cols-3 gap-4">
    @php
        $cards = [
            ['💰', 'Doações em dinheiro (mês)', 'R$ '.number_format($dinheiroMes, 2, ',', '.')],
            ['❤️', 'Doações recebidas (mês)', $doacoesMes],
            ['📦', 'Itens arrecadados (mês)', rtrim(rtrim(number_format($itensArrecadados, 2, ',', '.'), '0'), ',')],
            ['🚗', 'Voluntários ativos', $voluntarios],
            ['🎯', 'Campanhas ativas', $campanhasAtivas],
            ['🚨', 'Necessidades urgentes', $necessidadesUrgentes],
        ];
    @endphp
    @foreach ($cards as [$icone, $label, $valor])
        <div class="bg-white rounded-xl2 shadow-sm p-5">
            <div class="text-2xl">{{ $icone }}</div>
            <div class="mt-2 text-2xl font-extrabold text-marca">{{ $valor }}</div>
            <div class="text-xs text-cafe/60 mt-1">{{ $label }}</div>
        </div>
    @endforeach
</div>

<div class="mt-6 grid lg:grid-cols-2 gap-6">
    {{-- Alertas de estoque baixo --}}
    <div class="bg-white rounded-xl2 shadow-sm p-5">
        <h2 class="font-bold text-marca flex items-center gap-2">⚠️ Estoque baixo</h2>
        @if ($estoqueBaixo->isEmpty())
            <p class="mt-3 text-sm text-cafe/50">Nenhum item abaixo do mínimo. 🟢</p>
        @else
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($estoqueBaixo as $item)
                    <li class="flex justify-between items-center">
                        <span>🔴 {{ $item->nome }}</span>
                        <span class="text-cafe/60">{{ rtrim(rtrim(number_format($item->quantidade,2,',','.'),'0'),',') }} / mín {{ rtrim(rtrim(number_format($item->quantidade_minima,2,',','.'),'0'),',') }} {{ $item->unidade }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
        <a href="{{ route('admin.estoque.index') }}" class="mt-4 inline-block text-terracota text-sm font-semibold hover:underline">ver estoque →</a>
    </div>

    {{-- Ultimas doacoes --}}
    <div class="bg-white rounded-xl2 shadow-sm p-5">
        <h2 class="font-bold text-marca flex items-center gap-2">❤️ Últimas doações</h2>
        @if ($ultimasDoacoes->isEmpty())
            <p class="mt-3 text-sm text-cafe/50">Nenhuma doação registrada ainda.</p>
        @else
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($ultimasDoacoes as $d)
                    <li class="flex justify-between items-center">
                        <span>{{ $d->doador_exibicao }}</span>
                        <span class="font-semibold">
                            {{ $d->tipo === 'dinheiro' ? 'R$ '.number_format($d->valor,2,',','.') : (rtrim(rtrim(number_format($d->quantidade,2,',','.'),'0'),',').' '.($d->descricao ?: 'itens')) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
        <a href="{{ route('admin.doacoes.index') }}" class="mt-4 inline-block text-terracota text-sm font-semibold hover:underline">ver doações →</a>
    </div>
</div>
@endsection
