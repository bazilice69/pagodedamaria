@extends('layouts.publico')
@section('titulo', 'Verificação — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
<section class="max-w-md mx-auto px-4 sm:px-6 py-12">
    <div class="bg-white rounded-xl2 shadow-md p-6 text-center">
        <div class="text-green-600 text-4xl">✓</div>
        <h1 class="text-xl font-extrabold text-marca mt-2">Carteirinha válida</h1>
        <p class="text-sm text-cafe/60">{{ $assoc->nome ?? 'Pagode da Dona Maria' }}</p>

        <div class="mt-6 flex flex-col items-center gap-3">
            @if ($pessoa->foto && $pessoa->autoriza_imagem)
                <img src="{{ asset('storage/'.$pessoa->foto) }}" class="h-24 w-24 rounded-full object-cover">
            @else
                <span class="h-24 w-24 rounded-full bg-creme flex items-center justify-center text-3xl">👤</span>
            @endif
            <div>
                <div class="font-bold text-marca text-lg">{{ $pessoa->nome }}</div>
                <div class="text-sm text-cafe/60">ID: {{ $pessoa->codigo }}</div>
                <div class="flex flex-wrap justify-center gap-1 mt-2">
                    @foreach ($pessoa->papeis as $papelLabel)<span class="text-xs bg-creme px-2 py-0.5 rounded-full">{{ $papelLabel }}</span>@endforeach
                </div>
            </div>
        </div>

        <p class="mt-6 text-xs text-cafe/40">
            Esta página confirma apenas a identificação junto à associação.
            Dados pessoais não são exibidos publicamente.
        </p>
    </div>
</section>
@endsection
