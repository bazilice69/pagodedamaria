@extends('layouts.admin')
@section('titulo', $doacao->exists ? 'Editar doação' : 'Nova doação')

@section('conteudo')
<div class="max-w-2xl">
    <a href="{{ route('admin.doacoes.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $doacao->exists ? 'Editar doação' : 'Nova doação' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $doacao->exists ? route('admin.doacoes.update', $doacao) : route('admin.doacoes.store') }}"
          class="bg-white rounded-xl2 shadow-sm p-6 space-y-4"
          x-data="{ tipo: '{{ old('tipo', $doacao->tipo ?? 'item') }}', anonimo: {{ old('anonimo', $doacao->anonimo) ? 'true' : 'false' }} }">
        @csrf
        @if ($doacao->exists) @method('PUT') @endif

        {{-- Tipo --}}
        <div>
            <label class="block text-sm font-semibold mb-2">Tipo de doação</label>
            <div class="flex gap-3">
                <label class="flex-1 flex items-center gap-2 border rounded-lg px-4 py-3 cursor-pointer" :class="tipo==='item' ? 'border-marca bg-creme' : 'border-cafe/15'">
                    <input type="radio" name="tipo" value="item" x-model="tipo" class="text-marca focus:ring-marca"> 📦 Item físico
                </label>
                <label class="flex-1 flex items-center gap-2 border rounded-lg px-4 py-3 cursor-pointer" :class="tipo==='dinheiro' ? 'border-marca bg-creme' : 'border-cafe/15'">
                    <input type="radio" name="tipo" value="dinheiro" x-model="tipo" class="text-marca focus:ring-marca"> 💰 Dinheiro
                </label>
            </div>
        </div>

        {{-- Doador --}}
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Nome do doador</label>
                <input name="doador_nome" value="{{ old('doador_nome', $doacao->doador_nome) }}" :disabled="anonimo"
                       class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca disabled:bg-gray-100">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Contato (WhatsApp/tel.)</label>
                <input name="doador_contato" value="{{ old('doador_contato', $doacao->doador_contato) }}" :disabled="anonimo"
                       class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca disabled:bg-gray-100">
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="anonimo" value="1" x-model="anonimo" @checked(old('anonimo', $doacao->anonimo)) class="rounded text-marca focus:ring-marca"> Doação anônima
        </label>

        {{-- Campos de DINHEIRO --}}
        <div x-show="tipo==='dinheiro'" x-cloak>
            <label class="block text-sm font-semibold mb-1">Valor (R$)</label>
            <input type="number" step="0.01" min="0" name="valor" value="{{ old('valor', $doacao->valor) }}"
                   class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
        </div>

        {{-- Campos de ITEM --}}
        <div x-show="tipo==='item'" x-cloak class="space-y-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Descrição do item</label>
                <input name="descricao" value="{{ old('descricao', $doacao->descricao) }}" placeholder="Ex: 10 kg de arroz"
                       class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Quantidade</label>
                    <input type="number" step="0.01" min="0" name="quantidade" value="{{ old('quantidade', $doacao->quantidade) }}"
                           class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Unidade</label>
                    <input name="unidade" value="{{ old('unidade', $doacao->unidade ?? 'un') }}"
                           class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Vincular ao estoque</label>
                    <select name="item_id" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                        <option value="">— não vincular —</option>
                        @foreach ($itens as $it)
                            <option value="{{ $it->id }}" @selected(old('item_id', $doacao->item_id) == $it->id)>{{ $it->nome }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-cafe/50 mt-1">Se vincular, entra no estoque automaticamente.</p>
                </div>
            </div>
        </div>

        {{-- Vinculos e data --}}
        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Categoria</label>
                <select name="categoria_id" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                    <option value="">—</option>
                    @foreach ($categorias as $c)<option value="{{ $c->id }}" @selected(old('categoria_id', $doacao->categoria_id) == $c->id)>{{ $c->icone }} {{ $c->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Campanha</label>
                <select name="campanha_id" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                    <option value="">—</option>
                    @foreach ($campanhas as $c)<option value="{{ $c->id }}" @selected(old('campanha_id', $doacao->campanha_id) == $c->id)>{{ $c->titulo }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Data</label>
                <input type="date" name="data" value="{{ old('data', optional($doacao->data)->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                       class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Observação</label>
            <textarea name="observacao" rows="2" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">{{ old('observacao', $doacao->observacao) }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button class="btn-marca">{{ $doacao->exists ? 'Salvar' : 'Registrar doação' }}</button>
            <a href="{{ route('admin.doacoes.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
        </div>
    </form>
</div>
@endsection
