@extends('layouts.admin')
@section('titulo', 'Cursos')

@section('conteudo')
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">🎓 Cursos</h1>
        <p class="text-cafe/60 text-sm mt-1">Judô, balé, música, reforço... as atividades da associação.</p>
    </div>
    <a href="{{ route('admin.cursos.create') }}" class="btn-marca shrink-0">+ Novo curso</a>
</div>

{{-- Categorias editaveis --}}
<div class="bg-white rounded-xl2 shadow-sm p-5 mb-6" x-data="{ nova: false }">
    <div class="flex items-center justify-between">
        <h2 class="font-bold text-marca text-sm">Categorias de curso</h2>
        <button @click="nova = !nova" class="text-sm text-terracota font-semibold">+ nova categoria</button>
    </div>
    <div class="mt-3 flex flex-wrap gap-2">
        @forelse ($categorias as $cat)
            <span class="inline-flex items-center gap-2 bg-creme rounded-full px-3 py-1 text-sm">
                {{ $cat->icone }} {{ $cat->nome }}
                <form method="POST" action="{{ route('admin.categorias-curso.destroy', $cat) }}" onsubmit="return confirm('Remover categoria?')" class="inline">
                    @csrf @method('DELETE')
                    <button class="text-red-400 hover:text-red-600">×</button>
                </form>
            </span>
        @empty
            <span class="text-sm text-cafe/50">Nenhuma categoria ainda.</span>
        @endforelse
    </div>
    <form x-show="nova" x-cloak method="POST" action="{{ route('admin.categorias-curso.store') }}" class="mt-3 flex gap-2">
        @csrf
        <input name="icone" placeholder="🥋" maxlength="4" class="w-16 rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca">
        <input name="nome" placeholder="Nome da categoria (ex: Esportes)" required class="flex-1 rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca">
        <button class="btn-marca text-sm">Criar</button>
    </form>
</div>

@if ($cursos->isEmpty())
    <div class="bg-white rounded-xl2 shadow-sm p-10 text-center text-cafe/50">Nenhum curso cadastrado.</div>
@else
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($cursos as $c)
            <div class="bg-white rounded-xl2 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <h3 class="font-bold text-marca">{{ $c->categoria?->icone }} {{ $c->nome }}</h3>
                    <span class="text-xs px-2 py-1 rounded-full {{ $c->ativo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $c->ativo ? 'ativo' : 'inativo' }}</span>
                </div>
                <div class="mt-2 text-sm text-cafe/70 space-y-0.5">
                    @if ($c->professor)<p>👨‍🏫 {{ $c->professor->name }}</p>@endif
                    @if ($c->dias_semana || $c->horario)<p>🕒 {{ $c->dias_semana }} {{ $c->horario }}</p>@endif
                    <p>👧 {{ $c->matriculas_count }} aluno(s)@if($c->vagas) · {{ $c->vagas_restantes }} vaga(s)@endif</p>
                </div>
                <div class="mt-4 flex flex-wrap gap-3 text-sm">
                    <a href="{{ route('admin.cursos.show', $c) }}" class="text-marca font-semibold hover:underline">alunos</a>
                    <a href="{{ route('admin.cursos.presenca', $c) }}" class="text-marca font-semibold hover:underline">✅ chamada</a>
                    <a href="{{ route('admin.cursos.edit', $c) }}" class="text-cafe/60 hover:underline">editar</a>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
