@extends('layouts.admin')
@section('titulo', 'Cadastros')

@section('conteudo')
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">👥 Cadastros</h1>
        <p class="text-cafe/60 text-sm mt-1">Pessoas e famílias — alunos, responsáveis, voluntários, doadores.</p>
    </div>
    <a href="{{ route('admin.pessoas.create') }}" class="btn-marca shrink-0">+ Novo cadastro</a>
</div>

{{-- Busca e filtro --}}
<form method="GET" class="mb-5 flex flex-wrap gap-2">
    <input name="q" value="{{ $q }}" placeholder="🔎 Buscar por nome, código, CPF ou telefone..." class="flex-1 min-w-[200px] rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
    <select name="papel" class="rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
        <option value="">Todos os papéis</option>
        <option value="aluno" @selected($papel==='aluno')>Alunos</option>
        <option value="responsavel" @selected($papel==='responsavel')>Responsáveis</option>
        <option value="voluntario" @selected($papel==='voluntario')>Voluntários</option>
        <option value="doador" @selected($papel==='doador')>Doadores</option>
        <option value="beneficiario" @selected($papel==='beneficiario')>Beneficiários</option>
    </select>
    <button class="btn-marca">Filtrar</button>
</form>

<div class="bg-white rounded-xl2 shadow-sm overflow-hidden">
    @if ($pessoas->isEmpty())
        <div class="p-10 text-center text-cafe/50">Nenhum cadastro encontrado.</div>
    @else
        <div class="divide-y divide-creme">
            @foreach ($pessoas as $p)
                <a href="{{ route('admin.pessoas.show', $p) }}" class="flex items-center gap-4 p-4 hover:bg-creme/40">
                    @if ($p->foto)
                        <img src="{{ asset('storage/'.$p->foto) }}" class="h-12 w-12 rounded-full object-cover shrink-0">
                    @else
                        <span class="h-12 w-12 rounded-full bg-creme flex items-center justify-center text-xl shrink-0">👤</span>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-marca truncate">{{ $p->nome }}</div>
                        <div class="text-xs text-cafe/60">{{ $p->codigo }} @if($p->idade !== null)· {{ $p->idade }} anos @endif</div>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach ($p->papeis as $papelLabel)
                                <span class="text-xs bg-creme px-2 py-0.5 rounded-full">{{ $papelLabel }}</span>
                            @endforeach
                        </div>
                    </div>
                    <span class="text-cafe/30">›</span>
                </a>
            @endforeach
        </div>
    @endif
</div>

<div class="mt-4">{{ $pessoas->links() }}</div>
@endsection
