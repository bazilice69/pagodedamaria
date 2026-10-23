@extends('layouts.admin')
@section('titulo', 'Parceiros')

@section('conteudo')
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">🤝 Nossos Parceiros</h1>
        <p class="text-cafe/60 text-sm mt-1">Comércios e apoiadores que ajudam a associação (aparecem no site).</p>
    </div>
    <a href="{{ route('admin.parceiros.create') }}" class="btn-marca shrink-0">+ Novo parceiro</a>
</div>

@if ($parceiros->isEmpty())
    <div class="bg-white rounded-xl2 shadow-sm p-10 text-center text-cafe/50">Nenhum parceiro cadastrado.</div>
@else
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($parceiros as $p)
            <div class="bg-white rounded-xl2 shadow-sm p-5 flex items-center gap-4">
                @if ($p->logo)<img src="{{ asset('storage/'.$p->logo) }}" class="h-14 w-14 rounded-lg object-contain bg-creme p-1">@else<span class="h-14 w-14 rounded-lg bg-creme flex items-center justify-center text-2xl">🏪</span>@endif
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-marca truncate">{{ $p->nome }}</div>
                    @if ($p->categoria)<div class="text-xs text-cafe/60">{{ $p->categoria }}</div>@endif
                    <a href="{{ route('admin.parceiros.edit', $p) }}" class="text-marca text-sm font-semibold hover:underline">editar</a>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
