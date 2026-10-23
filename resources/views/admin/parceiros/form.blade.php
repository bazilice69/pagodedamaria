@extends('layouts.admin')
@section('titulo', $parceiro->exists ? 'Editar parceiro' : 'Novo parceiro')

@section('conteudo')
<div class="max-w-xl">
    <a href="{{ route('admin.parceiros.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $parceiro->exists ? 'Editar parceiro' : 'Novo parceiro' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" action="{{ $parceiro->exists ? route('admin.parceiros.update', $parceiro) : route('admin.parceiros.store') }}"
          class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
        @csrf
        @if ($parceiro->exists) @method('PUT') @endif

        <div><label class="block text-sm font-semibold mb-1">Nome *</label><input name="nome" value="{{ old('nome', $parceiro->nome) }}" required placeholder="Ex: Barbearia do Cleber" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-semibold mb-1">Categoria</label><input name="categoria" value="{{ old('categoria', $parceiro->categoria) }}" placeholder="Barbearia, Oficina..." class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            <div><label class="block text-sm font-semibold mb-1">Ordem</label><input type="number" min="0" name="ordem" value="{{ old('ordem', $parceiro->ordem ?? 0) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
        </div>
        <div><label class="block text-sm font-semibold mb-1">Site ou @ do Instagram</label><input name="site" value="{{ old('site', $parceiro->site) }}" placeholder="https://... ou @perfil" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
        <div><label class="block text-sm font-semibold mb-1">Frase curta</label><input name="frase" value="{{ old('frase', $parceiro->frase) }}" placeholder="Apoia nossas crianças 💜" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
        <div>
            <label class="block text-sm font-semibold mb-1">Logo</label>
            @if ($parceiro->logo)<img src="{{ asset('storage/'.$parceiro->logo) }}" class="h-16 rounded-lg mb-2 bg-creme p-1 object-contain">@endif
            <input type="file" name="logo" accept="image/*" class="text-sm">
        </div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ativo" value="1" @checked(old('ativo', $parceiro->ativo ?? true)) class="rounded text-marca focus:ring-marca"> Mostrar no site</label>

        <div class="flex gap-3 pt-2">
            <button class="btn-marca">{{ $parceiro->exists ? 'Salvar' : 'Cadastrar' }}</button>
            <a href="{{ route('admin.parceiros.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
            @if ($parceiro->exists)
                <form method="POST" action="{{ route('admin.parceiros.destroy', $parceiro) }}" class="ml-auto" onsubmit="return confirm('Remover parceiro?')">@csrf @method('DELETE')<button class="px-6 py-3 rounded-full font-semibold text-red-600 hover:bg-red-50">Remover</button></form>
            @endif
        </div>
    </form>
</div>
@endsection
