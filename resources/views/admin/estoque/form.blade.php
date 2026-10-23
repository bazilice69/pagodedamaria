@extends('layouts.admin')
@section('titulo', $item->exists ? 'Editar item' : 'Novo item')

@section('conteudo')
<div class="max-w-xl">
    <a href="{{ route('admin.estoque.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $item->exists ? 'Editar item' : 'Novo item' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $item->exists ? route('admin.estoque.update', $item) : route('admin.estoque.store') }}"
          class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-semibold mb-1">Nome do item *</label>
            <input name="nome" value="{{ old('nome', $item->nome) }}" required placeholder="Ex: Arroz" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Categoria</label>
            <select name="categoria_id" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                <option value="">—</option>
                @foreach ($categorias as $c)<option value="{{ $c->id }}" @selected(old('categoria_id', $item->categoria_id) == $c->id)>{{ $c->icone }} {{ $c->nome }}</option>@endforeach
            </select>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Unidade *</label>
                <input name="unidade" value="{{ old('unidade', $item->unidade ?? 'un') }}" required placeholder="kg, un, caixa" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Quantidade atual</label>
                <input type="number" step="0.01" min="0" name="quantidade" value="{{ old('quantidade', $item->quantidade ?? 0) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Estoque mínimo</label>
                <input type="number" step="0.01" min="0" name="quantidade_minima" value="{{ old('quantidade_minima', $item->quantidade_minima ?? 0) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                <p class="text-xs text-cafe/50 mt-1">Abaixo disso, dispara alerta.</p>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Observação</label>
            <textarea name="observacao" rows="2" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">{{ old('observacao', $item->observacao) }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button class="btn-marca">{{ $item->exists ? 'Salvar' : 'Cadastrar item' }}</button>
            <a href="{{ route('admin.estoque.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
        </div>
    </form>
</div>
@endsection
