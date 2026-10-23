@extends('layouts.admin')
@section('titulo', $modelo->exists ? 'Editar modelo' : 'Novo modelo de termo')

@section('conteudo')
<div class="max-w-2xl">
    <a href="{{ route('admin.termos.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $modelo->exists ? 'Editar modelo' : 'Novo modelo de termo' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $modelo->exists ? route('admin.termos.update', $modelo) : route('admin.termos.store') }}"
          class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
        @csrf
        @if ($modelo->exists) @method('PUT') @endif

        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-semibold mb-1">Título *</label><input name="titulo" value="{{ old('titulo', $modelo->titulo) }}" required placeholder="Ex: Autorização de passeio" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            <div>
                <label class="block text-sm font-semibold mb-1">Tipo</label>
                <select name="tipo" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                    @foreach (\App\Models\ModeloTermo::TIPOS as $v => $l)<option value="{{ $v }}" @selected(old('tipo', $modelo->tipo) === $v)>{{ $l }}</option>@endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Texto do termo</label>
            <textarea name="conteudo" rows="12" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca font-mono text-sm">{{ old('conteudo', $modelo->conteudo) }}</textarea>
        </div>

        <div class="bg-creme rounded-lg p-3 text-sm">
            <p class="font-semibold text-marca mb-1">🏷️ Campos que o sistema preenche sozinho:</p>
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-cafe/70">
                @foreach (\App\Models\ModeloTermo::PLACEHOLDERS as $ph => $desc)
                    <span><code class="bg-white px-1 rounded">{{ $ph }}</code> = {{ $desc }}</span>
                @endforeach
            </div>
        </div>

        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ativo" value="1" @checked(old('ativo', $modelo->ativo ?? true)) class="rounded text-marca focus:ring-marca"> Modelo ativo</label>

        <div class="flex gap-3 pt-2">
            <button class="btn-marca">{{ $modelo->exists ? 'Salvar' : 'Criar modelo' }}</button>
            <a href="{{ route('admin.termos.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
        </div>
    </form>

    @if ($modelo->exists)
        <div class="mt-6 border-t border-red-100 pt-4">
            <p class="text-xs text-cafe/50 mb-2">Zona de risco — esta ação apaga o modelo definitivamente.</p>
            <form method="POST" action="{{ route('admin.termos.destroy', $modelo) }}" onsubmit="return confirm('Remover este modelo de termo? Esta ação não pode ser desfeita.')">
                @csrf @method('DELETE')
                <button class="text-sm font-semibold text-red-600 border border-red-200 rounded-full px-4 py-2 hover:bg-red-50">🗑️ Remover modelo</button>
            </form>
        </div>
    @endif
</div>
@endsection
