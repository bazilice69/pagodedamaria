@extends('layouts.admin')
@section('titulo', 'Histórico — '.$item->nome)

@section('conteudo')
<a href="{{ route('admin.estoque.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar ao estoque</a>
<h1 class="text-2xl md:text-3xl font-extrabold text-marca mt-2">📜 Histórico: {{ $item->nome }}</h1>
<p class="text-cafe/60 text-sm mt-1">
    Saldo atual: <strong>{{ rtrim(rtrim(number_format($item->quantidade,2,',','.'),'0'),',') }} {{ $item->unidade }}</strong>.
    Cada entrada e saída registra <strong>quem fez</strong> e <strong>quem recebeu</strong>.
</p>

<div class="mt-6 bg-white rounded-xl2 shadow-sm overflow-hidden">
    @if ($movimentacoes->isEmpty())
        <div class="p-10 text-center text-cafe/50">Nenhuma movimentação registrada ainda.</div>
    @else
        {{-- Desktop --}}
        <table class="hidden md:table w-full text-sm">
            <thead class="bg-creme text-cafe/70 text-left">
                <tr>
                    <th class="px-4 py-3">Quando</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3 text-right">Qtd</th>
                    <th class="px-4 py-3">Quem recebeu / trouxe</th>
                    <th class="px-4 py-3">Responsável (quem registrou)</th>
                    <th class="px-4 py-3">Motivo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-creme">
                @foreach ($movimentacoes as $m)
                    <tr class="hover:bg-creme/40">
                        <td class="px-4 py-3 whitespace-nowrap">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $m->tipo === 'entrada' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $m->tipo === 'entrada' ? '➕ entrada' : '➖ saída' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold">{{ rtrim(rtrim(number_format($m->quantidade,2,',','.'),'0'),',') }} {{ $item->unidade }}</td>
                        <td class="px-4 py-3">
                            {{ $m->destinatario ?: '—' }}
                            @if ($m->destinatario_contato)<span class="block text-xs text-cafe/50">{{ $m->destinatario_contato }}</span>@endif
                        </td>
                        <td class="px-4 py-3">{{ $m->usuario?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-cafe/60">{{ $m->origem ?? '—' }}@if($m->observacao)<span class="block text-xs">{{ $m->observacao }}</span>@endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Mobile --}}
        <div class="md:hidden divide-y divide-creme">
            @foreach ($movimentacoes as $m)
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs px-2 py-1 rounded-full {{ $m->tipo === 'entrada' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $m->tipo === 'entrada' ? '➕ entrada' : '➖ saída' }}
                        </span>
                        <span class="font-bold">{{ rtrim(rtrim(number_format($m->quantidade,2,',','.'),'0'),',') }} {{ $item->unidade }}</span>
                    </div>
                    <div class="text-sm text-cafe/70 mt-2 space-y-0.5">
                        <p>👤 Recebeu/trouxe: <strong>{{ $m->destinatario ?: '—' }}</strong></p>
                        <p>✍️ Registrado por: {{ $m->usuario?->name ?? '—' }}</p>
                        <p class="text-xs text-cafe/50">{{ $m->created_at->format('d/m/Y H:i') }} · {{ $m->origem ?? '—' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="mt-4">{{ $movimentacoes->links() }}</div>
@endsection
