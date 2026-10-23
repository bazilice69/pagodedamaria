@extends('layouts.admin')
@section('titulo', $voluntario->exists ? 'Editar voluntário' : 'Novo voluntário')

@section('conteudo')
<div class="max-w-2xl">
    <a href="{{ route('admin.voluntarios.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $voluntario->exists ? 'Editar voluntário' : 'Novo voluntário' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $voluntario->exists ? route('admin.voluntarios.update', $voluntario) : route('admin.voluntarios.store') }}"
          class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
        @csrf
        @if ($voluntario->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-semibold mb-1">Nome *</label>
            <input name="nome" value="{{ old('nome', $voluntario->nome) }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">WhatsApp</label>
                <input name="whatsapp" value="{{ old('whatsapp', $voluntario->whatsapp) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">E-mail</label>
                <input type="email" name="email" value="{{ old('email', $voluntario->email) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-2">Áreas de atuação</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach ($areas as $valor => $rotulo)
                    <label class="flex items-center gap-2 text-sm bg-creme rounded-lg px-3 py-2 cursor-pointer">
                        <input type="checkbox" name="areas[]" value="{{ $valor }}" @checked(in_array($valor, old('areas', $voluntario->areas ?? []))) class="rounded text-marca focus:ring-marca"> {{ $rotulo }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Disponibilidade</label>
                <input name="disponibilidade" value="{{ old('disponibilidade', $voluntario->disponibilidade) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Região</label>
                <input name="regiao" value="{{ old('regiao', $voluntario->regiao) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Observação</label>
            <textarea name="observacao" rows="2" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">{{ old('observacao', $voluntario->observacao) }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ativo" value="1" @checked(old('ativo', $voluntario->ativo ?? true)) class="rounded text-marca focus:ring-marca"> Voluntário ativo</label>

        <div class="flex gap-3 pt-2">
            <button class="btn-marca">{{ $voluntario->exists ? 'Salvar' : 'Cadastrar' }}</button>
            <a href="{{ route('admin.voluntarios.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
        </div>
    </form>
</div>
@endsection
