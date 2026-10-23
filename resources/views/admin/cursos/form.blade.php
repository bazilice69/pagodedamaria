@extends('layouts.admin')
@section('titulo', $curso->exists ? 'Editar curso' : 'Novo curso')

@section('conteudo')
<div class="max-w-2xl">
    <a href="{{ route('admin.cursos.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $curso->exists ? 'Editar curso' : 'Novo curso' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $curso->exists ? route('admin.cursos.update', $curso) : route('admin.cursos.store') }}"
          class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
        @csrf
        @if ($curso->exists) @method('PUT') @endif

        <div><label class="block text-sm font-semibold mb-1">Nome do curso *</label><input name="nome" value="{{ old('nome', $curso->nome) }}" required placeholder="Ex: Judô infantil" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Categoria</label>
                <select name="categoria_curso_id" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                    <option value="">—</option>
                    @foreach ($categorias as $cat)<option value="{{ $cat->id }}" @selected(old('categoria_curso_id', $curso->categoria_curso_id) == $cat->id)>{{ $cat->icone }} {{ $cat->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Professor</label>
                <select name="professor_id" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                    <option value="">—</option>
                    @foreach ($professores as $prof)<option value="{{ $prof->id }}" @selected(old('professor_id', $curso->professor_id) == $prof->id)>{{ $prof->name }}</option>@endforeach
                </select>
            </div>
        </div>

        <div><label class="block text-sm font-semibold mb-1">Descrição</label><textarea name="descricao" rows="2" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">{{ old('descricao', $curso->descricao) }}</textarea></div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div><label class="block text-sm font-semibold mb-1">Dias</label><input name="dias_semana" value="{{ old('dias_semana', $curso->dias_semana) }}" placeholder="Seg, Qua" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            <div><label class="block text-sm font-semibold mb-1">Horário</label><input name="horario" value="{{ old('horario', $curso->horario) }}" placeholder="14h às 15h" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            <div><label class="block text-sm font-semibold mb-1">Local</label><input name="local" value="{{ old('local', $curso->local) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div><label class="block text-sm font-semibold mb-1">Vagas</label><input type="number" min="0" name="vagas" value="{{ old('vagas', $curso->vagas) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            <div><label class="block text-sm font-semibold mb-1">Idade mín.</label><input type="number" min="0" name="idade_min" value="{{ old('idade_min', $curso->idade_min) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            <div><label class="block text-sm font-semibold mb-1">Idade máx.</label><input type="number" min="0" name="idade_max" value="{{ old('idade_max', $curso->idade_max) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
        </div>

        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ativo" value="1" @checked(old('ativo', $curso->ativo ?? true)) class="rounded text-marca focus:ring-marca"> Curso ativo</label>

        <div class="flex gap-3 pt-2">
            <button class="btn-marca">{{ $curso->exists ? 'Salvar' : 'Criar curso' }}</button>
            <a href="{{ route('admin.cursos.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
        </div>
    </form>
</div>
@endsection
