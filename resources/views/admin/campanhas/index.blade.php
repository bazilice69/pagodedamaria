@extends('layouts.admin')
@section('titulo', 'Campanhas')

@section('conteudo')
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">🎯 Campanhas</h1>
        <p class="text-cafe/60 text-sm mt-1">Crie campanhas e acompanhe o progresso.</p>
    </div>
    <a href="{{ route('admin.campanhas.create') }}" class="btn-marca shrink-0">+ Nova campanha</a>
</div>

@if ($campanhas->isEmpty())
    <div class="bg-white rounded-xl2 shadow-sm p-10 text-center text-cafe/50">Nenhuma campanha cadastrada.</div>
@else
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($campanhas as $c)
            @php
                $fmt = fn($v) => $c->tipo_meta === 'dinheiro' ? 'R$ '.number_format($v, 2, ',', '.') : rtrim(rtrim(number_format($v, 2, ',', '.'), '0'), ',').' '.$c->unidade_meta;
            @endphp
            <div class="bg-white rounded-xl2 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <h3 class="font-bold text-marca">{{ $c->titulo }}</h3>
                    <span class="text-xs px-2 py-1 rounded-full {{ $c->ativa ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $c->ativa ? 'ativa' : 'inativa' }}</span>
                </div>
                <div class="mt-3">
                    <div class="flex justify-between text-sm mb-1"><span>{{ $fmt($c->arrecadado) }}</span><span class="text-terracota font-semibold">{{ $c->progresso }}%</span></div>
                    <div class="barra-progresso"><span style="width: {{ $c->progresso }}%"></span></div>
                    <p class="text-xs text-cafe/50 mt-1">Meta: {{ $fmt($c->meta) }}</p>
                </div>
                <div class="mt-4 flex gap-3 text-sm">
                    <a href="{{ route('admin.campanhas.edit', $c) }}" class="text-marca font-semibold hover:underline">editar</a>
                    <a href="{{ route('campanhas.show', $c) }}" target="_blank" class="text-cafe/60 hover:underline">ver no site ↗</a>
                    @include('admin.partials.botao-excluir', ['rota' => route('admin.campanhas.destroy', $c)])
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $campanhas->links() }}</div>
@endif
@endsection
