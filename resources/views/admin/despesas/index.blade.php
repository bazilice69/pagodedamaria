@extends('layouts.admin')
@section('titulo', 'Despesas')

@section('conteudo')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">💸 Despesas</h1>
        <p class="text-cafe/60 text-sm mt-1">Contas da associação (luz, água, aluguel, material...).</p>
    </div>
    <a href="{{ route('admin.despesas.create') }}" class="btn-marca shrink-0">+ Nova despesa</a>
</div>

<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <form method="GET" class="flex items-end gap-2">
        <div><label class="block text-xs font-semibold mb-1">Mês</label><input type="month" name="mes" value="{{ $mes }}" class="rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca"></div>
        <button class="px-4 py-2 rounded-full bg-white text-marca font-semibold shadow text-sm">Ver</button>
    </form>
    <div class="bg-white rounded-xl2 shadow-sm px-5 py-3">
        <span class="text-sm text-cafe/60">Total em {{ $labelMes }}:</span>
        <span class="text-xl font-extrabold text-red-600 ml-2">R$ {{ number_format($total, 2, ',', '.') }}</span>
    </div>
</div>

<div class="bg-white rounded-xl2 shadow-sm overflow-hidden">
    @if ($despesas->isEmpty())
        <div class="p-10 text-center text-cafe/50">Nenhuma despesa neste mês.</div>
    @else
        <div class="divide-y divide-creme">
            @foreach ($despesas as $d)
                <div class="flex items-center gap-4 p-4">
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-marca truncate">{{ $d->descricao }} @if($d->recorrente)<span class="text-xs text-cafe/40">🔁 fixa</span>@endif</div>
                        <div class="text-xs text-cafe/60">{{ $d->data->format('d/m/Y') }} @if($d->categoria)· {{ $d->categoria }}@endif · {{ $d->paga ? 'paga' : 'a pagar' }}</div>
                    </div>
                    <span class="font-bold text-red-600">R$ {{ number_format($d->valor, 2, ',', '.') }}</span>
                    <a href="{{ route('admin.despesas.edit', $d) }}" class="text-marca text-sm font-semibold hover:underline">editar</a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
