@extends('layouts.admin')
@section('titulo', 'Termos de Autorização')

@section('conteudo')
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">📋 Termos de Autorização</h1>
        <p class="text-cafe/60 text-sm mt-1">Modelos de passeio, uso de imagem etc. O sistema preenche os dados automaticamente.</p>
    </div>
    <a href="{{ route('admin.termos.create') }}" class="btn-marca shrink-0">+ Novo modelo</a>
</div>

@if ($modelos->isEmpty())
    <div class="bg-white rounded-xl2 shadow-sm p-10 text-center text-cafe/50">
        Nenhum modelo de termo. Crie um (ex: autorização de passeio).
    </div>
@else
    <div class="grid gap-4 md:grid-cols-2">
        @foreach ($modelos as $m)
            <div class="bg-white rounded-xl2 shadow-sm p-5" x-data="{ gerar: false }">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-bold text-marca">{{ $m->titulo }}</h3>
                        <span class="text-xs text-cafe/50">{{ \App\Models\ModeloTermo::TIPOS[$m->tipo] ?? $m->tipo }}</span>
                    </div>
                    <a href="{{ route('admin.termos.edit', $m) }}" class="text-marca text-sm font-semibold hover:underline">editar</a>
                </div>

                <button @click="gerar = !gerar" class="mt-4 rounded-full bg-marca text-white text-sm font-semibold px-4 py-2">📄 Gerar para uma pessoa</button>

                <form x-show="gerar" x-cloak method="POST" action="{{ route('admin.termos.gerar', $m) }}" class="mt-3 flex gap-2">
                    @csrf
                    <select name="pessoa_id" required class="flex-1 rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca">
                        <option value="">Selecione a pessoa...</option>
                        @foreach (\App\Models\Pessoa::orderBy('nome')->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->nome }} ({{ $p->codigo }})</option>
                        @endforeach
                    </select>
                    <button class="btn-marca text-sm">Gerar</button>
                </form>
            </div>
        @endforeach
    </div>
@endif

@if ($gerados->isNotEmpty())
    <h2 class="mt-8 font-bold text-marca">Termos gerados recentemente</h2>
    <div class="mt-3 bg-white rounded-xl2 shadow-sm divide-y divide-creme">
        @foreach ($gerados as $g)
            <div class="px-4 py-3 flex items-center justify-between text-sm">
                <span>{{ $g->titulo }} — <strong>{{ $g->pessoa?->nome ?? '—' }}</strong></span>
                <a href="{{ route('admin.termos.imprimir', $g) }}" target="_blank" class="text-terracota font-semibold hover:underline">imprimir ↗</a>
            </div>
        @endforeach
    </div>
@endif
@endsection
