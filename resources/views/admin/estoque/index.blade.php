@extends('layouts.admin')
@section('titulo', 'Estoque')

@section('conteudo')
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">📦 Estoque</h1>
        <p class="text-cafe/60 text-sm mt-1">Tudo que a associação tem disponível.</p>
    </div>
    <a href="{{ route('admin.estoque.create') }}" class="btn-marca shrink-0">+ Novo item</a>
</div>

@if ($itens->isEmpty())
    <div class="bg-white rounded-xl2 shadow-sm p-10 text-center text-cafe/50">Nenhum item no estoque.</div>
@else
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" x-data="{ mov: null }">
        @foreach ($itens as $item)
            @php
                $status = $item->status_estoque;
                $cor = ['baixo'=>'🔴','atencao'=>'🟡','normal'=>'🟢'][$status];
                $txt = ['baixo'=>'Estoque baixo','atencao'=>'Atenção','normal'=>'Estoque normal'][$status];
            @endphp
            <div class="bg-white rounded-xl2 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-bold text-marca">{{ $item->nome }}</h3>
                        @if ($item->categoria)<span class="text-xs text-cafe/50">{{ $item->categoria->icone }} {{ $item->categoria->nome }}</span>@endif
                    </div>
                    <span class="text-xs">{{ $cor }} {{ $txt }}</span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-cafe">{{ rtrim(rtrim(number_format($item->quantidade,2,',','.'),'0'),',') }}</span>
                    <span class="text-sm text-cafe/60">{{ $item->unidade }}</span>
                </div>
                <p class="text-xs text-cafe/50">Mínimo: {{ rtrim(rtrim(number_format($item->quantidade_minima,2,',','.'),'0'),',') }} {{ $item->unidade }}</p>

                <div class="mt-4 flex flex-wrap gap-2 text-sm">
                    <button @click="mov = {{ $item->id }}" class="rounded-full bg-marca text-white px-3 py-1.5 text-xs font-semibold">+ movimentar</button>
                    <a href="{{ route('admin.estoque.edit', $item) }}" class="text-marca font-semibold self-center hover:underline">editar</a>
                    @include('admin.partials.botao-excluir', ['rota' => route('admin.estoque.destroy', $item)])
                </div>

                {{-- Modal de movimentacao --}}
                <div x-show="mov === {{ $item->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40" @click.self="mov = null">
                    <div class="bg-white rounded-xl2 p-6 w-full max-w-sm">
                        <h4 class="font-bold text-marca mb-4">Movimentar: {{ $item->nome }}</h4>
                        <form method="POST" action="{{ route('admin.estoque.movimentar', $item) }}" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center justify-center gap-2 border rounded-lg py-2 cursor-pointer has-[:checked]:border-marca has-[:checked]:bg-creme">
                                    <input type="radio" name="tipo" value="entrada" checked class="text-marca"> ➕ Entrada
                                </label>
                                <label class="flex items-center justify-center gap-2 border rounded-lg py-2 cursor-pointer has-[:checked]:border-marca has-[:checked]:bg-creme">
                                    <input type="radio" name="tipo" value="saida" class="text-marca"> ➖ Saída
                                </label>
                            </div>
                            <input type="number" step="0.01" min="0.01" name="quantidade" required placeholder="Quantidade" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                            <input name="origem" placeholder="Origem (ex: doação, distribuição)" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                            <div class="flex gap-2 pt-1">
                                <button class="btn-marca flex-1">Registrar</button>
                                <button type="button" @click="mov = null" class="px-4 py-2 rounded-full text-cafe/60 hover:bg-creme">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
