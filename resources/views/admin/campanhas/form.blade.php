@extends('layouts.admin')
@section('titulo', $campanha->exists ? 'Editar campanha' : 'Nova campanha')

@section('conteudo')
<div class="max-w-2xl">
    <a href="{{ route('admin.campanhas.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $campanha->exists ? 'Editar campanha' : 'Nova campanha' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $campanha->exists ? route('admin.campanhas.update', $campanha) : route('admin.campanhas.store') }}"
          class="bg-white rounded-xl2 shadow-sm p-6 space-y-4"
          x-data="{ tipo: '{{ old('tipo_meta', $campanha->tipo_meta ?? 'dinheiro') }}' }">
        @csrf
        @if ($campanha->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-semibold mb-1">Título *</label>
            <input name="titulo" value="{{ old('titulo', $campanha->titulo) }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Descrição</label>
            <textarea name="descricao" rows="3" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">{{ old('descricao', $campanha->descricao) }}</textarea>
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Meta é em...</label>
                <select name="tipo_meta" x-model="tipo" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                    <option value="dinheiro">💰 Dinheiro (R$)</option>
                    <option value="itens">📦 Itens</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Meta</label>
                <input type="number" step="0.01" min="0" name="meta" value="{{ old('meta', $campanha->meta) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div x-show="tipo==='itens'" x-cloak>
                <label class="block text-sm font-semibold mb-1">Unidade</label>
                <input name="unidade_meta" value="{{ old('unidade_meta', $campanha->unidade_meta) }}" placeholder="ex: kits, kg" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Início</label>
                <input type="date" name="data_inicio" value="{{ old('data_inicio', optional($campanha->data_inicio)->format('Y-m-d')) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Encerramento</label>
                <input type="date" name="data_fim" value="{{ old('data_fim', optional($campanha->data_fim)->format('Y-m-d')) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Foto da campanha</label>
            @if ($campanha->foto)<img src="{{ asset('storage/'.$campanha->foto) }}" class="h-24 rounded-lg mb-2 object-cover">@endif
            <input type="file" name="foto" accept="image/*" class="w-full text-sm">
            <p class="text-xs text-cafe/50 mt-1">JPG/PNG até 4 MB.</p>
        </div>

        <div class="flex flex-wrap gap-6">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ativa" value="1" @checked(old('ativa', $campanha->ativa ?? true)) class="rounded text-marca focus:ring-marca"> Campanha ativa</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="publica" value="1" @checked(old('publica', $campanha->publica ?? true)) class="rounded text-marca focus:ring-marca"> Mostrar no site</label>
        </div>

        <div class="flex gap-3 pt-2">
            <button class="btn-marca">{{ $campanha->exists ? 'Salvar' : 'Criar campanha' }}</button>
            <a href="{{ route('admin.campanhas.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
        </div>
    </form>
</div>
@endsection
