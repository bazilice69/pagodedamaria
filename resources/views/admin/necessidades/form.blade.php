@extends('layouts.admin')
@section('titulo', $necessidade->exists ? 'Editar necessidade' : 'Nova necessidade')

@section('conteudo')
<div class="max-w-2xl">
    <a href="{{ route('admin.necessidades.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $necessidade->exists ? 'Editar necessidade' : 'Nova necessidade' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $necessidade->exists ? route('admin.necessidades.update', $necessidade) : route('admin.necessidades.store') }}"
          class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
        @csrf
        @if ($necessidade->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-semibold mb-1">O que está precisando? *</label>
            <input name="titulo" value="{{ old('titulo', $necessidade->titulo) }}" required placeholder="Ex: Leite" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Descrição</label>
            <textarea name="descricao" rows="2" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">{{ old('descricao', $necessidade->descricao) }}</textarea>
        </div>

        <div class="grid sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Meta *</label>
                <input type="number" step="0.01" min="0" name="meta" value="{{ old('meta', $necessidade->meta) }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Unidade *</label>
                <input name="unidade" value="{{ old('unidade', $necessidade->unidade ?? 'un') }}" required placeholder="caixas" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Já arrecadado</label>
                <input type="number" step="0.01" min="0" name="arrecadado" value="{{ old('arrecadado', $necessidade->arrecadado ?? 0) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Prioridade</label>
                <select name="prioridade" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                    @foreach (['urgente'=>'🔴 Urgente','alta'=>'🟠 Alta','media'=>'🟡 Média','baixa'=>'⚪ Baixa'] as $v => $l)
                        <option value="{{ $v }}" @selected(old('prioridade', $necessidade->prioridade ?? 'media') === $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Categoria</label>
                <select name="categoria_id" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                    <option value="">—</option>
                    @foreach ($categorias as $c)<option value="{{ $c->id }}" @selected(old('categoria_id', $necessidade->categoria_id) == $c->id)>{{ $c->icone }} {{ $c->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Prazo</label>
                <input type="date" name="prazo" value="{{ old('prazo', optional($necessidade->prazo)->format('Y-m-d')) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
        </div>

        <div class="flex flex-wrap gap-6">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="publica" value="1" @checked(old('publica', $necessidade->publica ?? true)) class="rounded text-marca focus:ring-marca"> Mostrar no site</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="concluida" value="1" @checked(old('concluida', $necessidade->concluida)) class="rounded text-marca focus:ring-marca"> Concluída</label>
        </div>

        <div class="flex gap-3 pt-2">
            <button class="btn-marca">{{ $necessidade->exists ? 'Salvar' : 'Cadastrar' }}</button>
            <a href="{{ route('admin.necessidades.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
        </div>
    </form>
</div>
@endsection
