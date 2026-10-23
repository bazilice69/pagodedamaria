@extends('layouts.admin')
@section('titulo', 'Doações')

@section('conteudo')
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">❤️ Doações</h1>
        <p class="text-cafe/60 text-sm mt-1">Registro de tudo que a associação recebe.</p>
    </div>
    <a href="{{ route('admin.doacoes.create') }}" class="btn-marca shrink-0">+ Nova doação</a>
</div>

<div class="bg-white rounded-xl2 shadow-sm overflow-hidden">
    @if ($doacoes->isEmpty())
        <div class="p-10 text-center text-cafe/50">Nenhuma doação registrada ainda.</div>
    @else
        {{-- Tabela no desktop --}}
        <table class="hidden md:table w-full text-sm">
            <thead class="bg-creme text-cafe/70 text-left">
                <tr>
                    <th class="px-4 py-3">Data</th>
                    <th class="px-4 py-3">Doador</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Descrição</th>
                    <th class="px-4 py-3 text-right">Valor / Qtd</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-creme">
                @foreach ($doacoes as $d)
                    <tr class="hover:bg-creme/40">
                        <td class="px-4 py-3">{{ optional($d->data)->format('d/m/Y') ?? $d->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $d->doador_exibicao }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $d->tipo === 'dinheiro' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $d->tipo === 'dinheiro' ? '💰 Dinheiro' : '📦 Item' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $d->descricao ?? ($d->item?->nome ?? '—') }}</td>
                        <td class="px-4 py-3 text-right font-semibold">
                            {{ $d->tipo === 'dinheiro' ? 'R$ '.number_format($d->valor, 2, ',', '.') : rtrim(rtrim(number_format($d->quantidade, 2, ',', '.'), '0'), ',').' '.$d->unidade }}
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.doacoes.edit', $d) }}" class="text-marca hover:underline">editar</a>
                            @include('admin.partials.botao-excluir', ['rota' => route('admin.doacoes.destroy', $d)])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Cards no mobile --}}
        <div class="md:hidden divide-y divide-creme">
            @foreach ($doacoes as $d)
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-semibold">{{ $d->doador_exibicao }}</div>
                            <div class="text-xs text-cafe/60">{{ optional($d->data)->format('d/m/Y') ?? $d->created_at->format('d/m/Y') }}</div>
                        </div>
                        <span class="font-bold text-marca">
                            {{ $d->tipo === 'dinheiro' ? 'R$ '.number_format($d->valor, 2, ',', '.') : rtrim(rtrim(number_format($d->quantidade, 2, ',', '.'), '0'), ',').' '.$d->unidade }}
                        </span>
                    </div>
                    <div class="text-sm text-cafe/70 mt-1">{{ $d->descricao ?? ($d->item?->nome ?? '—') }}</div>
                    <div class="mt-2 flex gap-3 text-sm">
                        <a href="{{ route('admin.doacoes.edit', $d) }}" class="text-marca font-semibold">editar</a>
                        @include('admin.partials.botao-excluir', ['rota' => route('admin.doacoes.destroy', $d)])
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="mt-4">{{ $doacoes->links() }}</div>
@endsection
