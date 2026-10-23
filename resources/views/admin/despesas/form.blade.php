@extends('layouts.admin')
@section('titulo', $despesa->exists ? 'Editar despesa' : 'Nova despesa')

@section('conteudo')
<div class="max-w-xl">
    <a href="{{ route('admin.despesas.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $despesa->exists ? 'Editar despesa' : 'Nova despesa' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $despesa->exists ? route('admin.despesas.update', $despesa) : route('admin.despesas.store') }}"
          class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
        @csrf
        @if ($despesa->exists) @method('PUT') @endif

        <div><label class="block text-sm font-semibold mb-1">Descrição *</label><input name="descricao" value="{{ old('descricao', $despesa->descricao) }}" required placeholder="Ex: Conta de luz" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Categoria</label>
                <input name="categoria" list="cats" value="{{ old('categoria', $despesa->categoria) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                <datalist id="cats">@foreach (\App\Models\Despesa::CATEGORIAS as $c)<option value="{{ $c }}">@endforeach</datalist>
            </div>
            <div><label class="block text-sm font-semibold mb-1">Valor (R$) *</label><input type="number" step="0.01" min="0" name="valor" value="{{ old('valor', $despesa->valor) }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            <div><label class="block text-sm font-semibold mb-1">Data *</label><input type="date" name="data" value="{{ old('data', optional($despesa->data)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
        </div>

        <div class="flex flex-wrap gap-6">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="paga" value="1" @checked(old('paga', $despesa->paga ?? true)) class="rounded text-marca focus:ring-marca"> Já paga</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="recorrente" value="1" @checked(old('recorrente', $despesa->recorrente)) class="rounded text-marca focus:ring-marca"> 🔁 Conta fixa mensal</label>
        </div>

        <div><label class="block text-sm font-semibold mb-1">Observação</label><textarea name="observacao" rows="2" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">{{ old('observacao', $despesa->observacao) }}</textarea></div>

        <div class="flex gap-3 pt-2">
            <button class="btn-marca">{{ $despesa->exists ? 'Salvar' : 'Registrar' }}</button>
            <a href="{{ route('admin.despesas.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
            @if ($despesa->exists)
                <form method="POST" action="{{ route('admin.despesas.destroy', $despesa) }}" class="ml-auto" onsubmit="return confirm('Remover despesa?')">@csrf @method('DELETE')<button class="px-6 py-3 rounded-full font-semibold text-red-600 hover:bg-red-50">Remover</button></form>
            @endif
        </div>
    </form>
</div>
@endsection
