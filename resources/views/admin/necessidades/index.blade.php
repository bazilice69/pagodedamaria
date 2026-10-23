@extends('layouts.admin')
@section('titulo', 'Necessidades')

@section('conteudo')
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">🚨 Necessidades</h1>
        <p class="text-cafe/60 text-sm mt-1">O que a associação está precisando agora.</p>
    </div>
    <a href="{{ route('admin.necessidades.create') }}" class="btn-marca shrink-0">+ Nova necessidade</a>
</div>

@if ($necessidades->isEmpty())
    <div class="bg-white rounded-xl2 shadow-sm p-10 text-center text-cafe/50">Nenhuma necessidade cadastrada.</div>
@else
    <div class="grid gap-4 md:grid-cols-2">
        @foreach ($necessidades as $n)
            @php
                $badge = ['urgente'=>'bg-red-100 text-red-700','alta'=>'bg-orange-100 text-orange-700','media'=>'bg-yellow-100 text-yellow-800','baixa'=>'bg-gray-100 text-gray-600'][$n->prioridade];
            @endphp
            <div class="bg-white rounded-xl2 shadow-sm p-5 {{ $n->concluida ? 'opacity-60' : '' }}">
                <div class="flex items-start justify-between">
                    <h3 class="font-bold text-marca">{{ $n->titulo }} @if($n->concluida)<span class="text-green-600 text-sm">✓ concluída</span>@endif</h3>
                    <span class="text-xs px-2 py-1 rounded-full {{ $badge }}">{{ ucfirst($n->prioridade) }}</span>
                </div>
                <div class="mt-3">
                    <div class="flex justify-between text-sm mb-1">
                        <span>{{ rtrim(rtrim(number_format($n->arrecadado,2,',','.'),'0'),',') }} / {{ rtrim(rtrim(number_format($n->meta,2,',','.'),'0'),',') }} {{ $n->unidade }}</span>
                        <span class="text-terracota font-semibold">{{ $n->progresso }}%</span>
                    </div>
                    <div class="barra-progresso"><span style="width: {{ $n->progresso }}%"></span></div>
                </div>
                <div class="mt-4 flex gap-3 text-sm">
                    <a href="{{ route('admin.necessidades.edit', $n) }}" class="text-marca font-semibold hover:underline">editar</a>
                    @include('admin.partials.botao-excluir', ['rota' => route('admin.necessidades.destroy', $n)])
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $necessidades->links() }}</div>
@endif
@endsection
