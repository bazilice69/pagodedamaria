@extends('layouts.admin')
@section('titulo', 'Chamada — '.$curso->nome)

@section('conteudo')
<a href="{{ route('admin.cursos.show', $curso) }}" class="text-terracota text-sm font-semibold hover:underline">← voltar ao curso</a>
<h1 class="text-2xl font-extrabold text-marca mt-2">✅ Chamada: {{ $curso->nome }}</h1>

{{-- Seletor de data --}}
<form method="GET" class="mt-4 flex items-end gap-2">
    <div>
        <label class="block text-sm font-semibold mb-1">Data da aula</label>
        <input type="date" name="data" value="{{ $data }}" class="rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
    </div>
    <button class="px-5 py-2.5 rounded-full bg-white text-marca font-semibold shadow hover:bg-creme">Ver data</button>
</form>

@if ($curso->alunos->isEmpty())
    <div class="mt-6 bg-white rounded-xl2 shadow-sm p-10 text-center text-cafe/50">
        Nenhum aluno matriculado. <a href="{{ route('admin.cursos.show', $curso) }}" class="text-terracota hover:underline">matricular alunos</a>
    </div>
@else
    <form method="POST" action="{{ route('admin.cursos.presenca.store', $curso) }}" class="mt-6"
          x-data="{ marcarTodos(v){ this.$root.querySelectorAll('input[name=\'presentes[]\']').forEach(c => c.checked = v); } }">
        @csrf
        <input type="hidden" name="data" value="{{ $data }}">

        <div class="bg-white rounded-xl2 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-creme flex items-center justify-between">
                <span class="font-bold text-marca text-sm">{{ $curso->alunos->count() }} aluno(s) · {{ \Illuminate\Support\Carbon::parse($data)->format('d/m/Y') }}</span>
                <div class="flex gap-2 text-xs">
                    <button type="button" @click="marcarTodos(true)" class="text-green-600 font-semibold">marcar todos</button>
                    <button type="button" @click="marcarTodos(false)" class="text-cafe/50 font-semibold">limpar</button>
                </div>
            </div>
            <div class="divide-y divide-creme">
                @foreach ($curso->alunos as $aluno)
                    <label class="flex items-center gap-3 p-4 cursor-pointer hover:bg-creme/40">
                        <input type="checkbox" name="presentes[]" value="{{ $aluno->id }}" @checked($registradas[$aluno->id] ?? false) class="h-5 w-5 rounded text-marca focus:ring-marca">
                        @if ($aluno->foto)<img src="{{ asset('storage/'.$aluno->foto) }}" class="h-9 w-9 rounded-full object-cover">@else<span class="h-9 w-9 rounded-full bg-creme flex items-center justify-center text-sm">👤</span>@endif
                        <span class="font-semibold text-marca">{{ $aluno->nome }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <button class="btn-marca mt-4">💾 Salvar chamada</button>
    </form>
@endif
@endsection
