@extends('layouts.publico')
@section('titulo', $campanha->titulo.' — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
@php
    $unidade = $campanha->tipo_meta === 'dinheiro' ? '' : ' '.$campanha->unidade_meta;
    $fmt = fn($v) => $campanha->tipo_meta === 'dinheiro'
        ? 'R$ '.number_format($v, 2, ',', '.')
        : rtrim(rtrim(number_format($v, 2, ',', '.'), '0'), ',').$unidade;
    $texto = rawurlencode("🎯 Campanha \"{$campanha->titulo}\" do ".($assoc->nome ?? 'Pagode da Dona Maria').".\n💜 Ajude você também! ".route('campanhas.show', $campanha));
@endphp
<section class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
    @if ($campanha->foto)
        <img src="{{ asset('storage/'.$campanha->foto) }}" alt="{{ $campanha->titulo }}" class="w-full h-64 object-cover rounded-xl2">
    @else
        <div class="w-full h-64 rounded-xl2 bg-gradient-to-br from-marca to-terracota flex items-center justify-center text-7xl">🎯</div>
    @endif

    <h1 class="mt-6 text-3xl font-extrabold text-marca">{{ $campanha->titulo }}</h1>
    @if ($campanha->descricao)<p class="mt-3 text-cafe/80 whitespace-pre-line">{{ $campanha->descricao }}</p>@endif

    <div class="mt-6 bg-white rounded-xl2 p-6 shadow-sm">
        <div class="flex justify-between font-bold mb-2">
            <span>{{ $fmt($campanha->arrecadado) }} arrecadado</span>
            <span class="text-terracota">{{ $campanha->progresso }}%</span>
        </div>
        <div class="barra-progresso"><span style="width: {{ $campanha->progresso }}%"></span></div>
        <p class="text-sm text-cafe/60 mt-2">Meta: {{ $fmt($campanha->meta) }}</p>

        <div class="mt-5 flex flex-wrap gap-3">
            <a href="{{ route('como-ajudar') }}" class="btn-terracota">❤️ Quero ajudar</a>
            <a target="_blank" href="https://wa.me/?text={{ $texto }}" class="inline-flex items-center gap-2 rounded-full bg-green-500 text-white font-semibold px-6 py-3 hover:brightness-95 transition">📲 Compartilhar</a>
        </div>
    </div>

    <a href="{{ route('campanhas.index') }}" class="inline-block mt-6 text-terracota font-semibold hover:underline">← voltar para campanhas</a>
</section>
@endsection
