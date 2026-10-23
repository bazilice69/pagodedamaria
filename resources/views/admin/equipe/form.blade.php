@extends('layouts.admin')
@section('titulo', $membro->exists ? 'Editar membro' : 'Novo membro')

@section('conteudo')
<div class="max-w-xl">
    <a href="{{ route('admin.equipe.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $membro->exists ? 'Editar membro' : 'Novo membro da equipe' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $membro->exists ? route('admin.equipe.update', $membro) : route('admin.equipe.store') }}"
          class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
        @csrf
        @if ($membro->exists) @method('PUT') @endif

        <div><label class="block text-sm font-semibold mb-1">Nome *</label><input name="name" value="{{ old('name', $membro->name) }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-semibold mb-1">E-mail (login) *</label><input type="email" name="email" value="{{ old('email', $membro->email) }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            <div><label class="block text-sm font-semibold mb-1">Telefone</label><input name="telefone" value="{{ old('telefone', $membro->telefone) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Nível de acesso *</label>
                <select name="role" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                    @foreach ($niveis as $valor => $label)
                        <option value="{{ $valor }}" @selected(old('role', $membro->role) === $valor)>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-cafe/50 mt-1">Vice e Diretor têm acesso de gestão. Para presidente, use "Transferir presidência".</p>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Cargo (título na carteirinha)</label>
                <input name="cargo" value="{{ old('cargo', $membro->cargo) }}" placeholder="Ex: Diretor Financeiro, Professor de Judô" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Senha {{ $membro->exists ? '(deixe em branco para manter)' : '*' }}</label>
            <input type="password" name="password" {{ $membro->exists ? '' : 'required' }} class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
        </div>

        @if ($membro->exists)
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ativo" value="1" @checked(old('ativo', $membro->ativo)) class="rounded text-marca focus:ring-marca"> Acesso ativo</label>
        @endif

        <div class="flex gap-3 pt-2">
            <button class="btn-marca">{{ $membro->exists ? 'Salvar' : 'Cadastrar membro' }}</button>
            <a href="{{ route('admin.equipe.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
            @if ($membro->exists)
                <form method="POST" action="{{ route('admin.equipe.destroy', $membro) }}" class="ml-auto" onsubmit="return confirm('Remover este membro da equipe?')">
                    @csrf @method('DELETE')
                    <button class="px-6 py-3 rounded-full font-semibold text-red-600 hover:bg-red-50">Remover</button>
                </form>
            @endif
        </div>
    </form>
</div>
@endsection
