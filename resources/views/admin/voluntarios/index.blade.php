@extends('layouts.admin')
@section('titulo', 'Voluntários')

@section('conteudo')
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">🚗 Voluntários</h1>
        <p class="text-cafe/60 text-sm mt-1">Quem ajuda a fazer tudo acontecer.</p>
    </div>
    <a href="{{ route('admin.voluntarios.create') }}" class="btn-marca shrink-0">+ Novo voluntário</a>
</div>

@if ($voluntarios->isEmpty())
    <div class="bg-white rounded-xl2 shadow-sm p-10 text-center text-cafe/50">Nenhum voluntário cadastrado.</div>
@else
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($voluntarios as $v)
            <div class="bg-white rounded-xl2 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <h3 class="font-bold text-marca">{{ $v->nome }}</h3>
                    <span class="text-xs px-2 py-1 rounded-full {{ $v->ativo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $v->ativo ? 'ativo' : 'inativo' }}</span>
                </div>
                @if ($v->whatsapp)
                    <a target="_blank" href="https://wa.me/{{ preg_replace('/\D/','',$v->whatsapp) }}" class="text-sm text-green-600 hover:underline mt-1 inline-block">💬 {{ $v->whatsapp }}</a>
                @endif
                @if (!empty($v->areas))
                    <div class="mt-2 flex flex-wrap gap-1">
                        @foreach ($v->areas as $a)<span class="text-xs bg-creme px-2 py-0.5 rounded-full">{{ \App\Models\Voluntario::AREAS[$a] ?? $a }}</span>@endforeach
                    </div>
                @endif
                @if ($v->regiao)<p class="text-xs text-cafe/50 mt-2">📍 {{ $v->regiao }}</p>@endif
                <div class="mt-4 flex gap-3 text-sm">
                    <a href="{{ route('admin.voluntarios.edit', $v) }}" class="text-marca font-semibold hover:underline">editar</a>
                    @include('admin.partials.botao-excluir', ['rota' => route('admin.voluntarios.destroy', $v)])
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $voluntarios->links() }}</div>
@endif
@endsection
