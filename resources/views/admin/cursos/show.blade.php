@extends('layouts.admin')
@section('titulo', $curso->nome)

@section('conteudo')
<a href="{{ route('admin.cursos.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
<div class="flex items-center justify-between gap-4 mt-2 mb-6">
    <h1 class="text-2xl font-extrabold text-marca">{{ $curso->categoria?->icone }} {{ $curso->nome }}</h1>
    <div class="flex gap-2 shrink-0">
        <a href="{{ route('admin.cursos.relatorio', $curso) }}" class="px-4 py-2 rounded-full bg-white text-marca font-semibold shadow text-sm self-center">📊 Frequência</a>
        <a href="{{ route('admin.cursos.presenca', $curso) }}" class="btn-marca text-sm">✅ Fazer chamada</a>
    </div>
</div>

{{-- Matricular aluno --}}
<div class="bg-white rounded-xl2 shadow-sm p-5 mb-6">
    <h2 class="font-bold text-marca text-sm mb-3">Matricular aluno</h2>
    <form method="POST" action="{{ route('admin.cursos.matricular', $curso) }}" class="flex flex-wrap gap-2">
        @csrf
        <select name="pessoa_id" required class="flex-1 min-w-[200px] rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            <option value="">Selecione um aluno...</option>
            @foreach ($disponiveis as $d)<option value="{{ $d->id }}">{{ $d->nome }} ({{ $d->codigo }})</option>@endforeach
        </select>
        <button class="btn-marca">+ Matricular</button>
    </form>
    @if ($disponiveis->isEmpty())
        <p class="text-xs text-cafe/50 mt-2">Todos os alunos cadastrados já estão neste curso — ou não há alunos cadastrados ainda. <a href="{{ route('admin.pessoas.create') }}" class="text-terracota hover:underline">cadastrar pessoa</a></p>
    @endif
</div>

{{-- Alunos matriculados --}}
<div class="bg-white rounded-xl2 shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b border-creme font-bold text-marca text-sm">Alunos matriculados ({{ $curso->alunos->count() }})</div>
    @if ($curso->alunos->isEmpty())
        <div class="p-8 text-center text-cafe/50 text-sm">Nenhum aluno matriculado ainda.</div>
    @else
        <div class="divide-y divide-creme">
            @foreach ($curso->alunos as $aluno)
                <div class="flex items-center gap-3 p-4">
                    @if ($aluno->foto)<img src="{{ asset('storage/'.$aluno->foto) }}" class="h-10 w-10 rounded-full object-cover">@else<span class="h-10 w-10 rounded-full bg-creme flex items-center justify-center">👤</span>@endif
                    <a href="{{ route('admin.pessoas.show', $aluno) }}" class="flex-1 font-semibold text-marca hover:underline">{{ $aluno->nome }}</a>
                    <span class="text-xs text-cafe/50">{{ $aluno->codigo }}</span>
                    <form method="POST" action="{{ route('admin.cursos.matriculas.destroy', [$curso, $aluno->pivot->id]) }}" onsubmit="return confirm('Remover matrícula?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:text-red-700 text-sm">remover</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
