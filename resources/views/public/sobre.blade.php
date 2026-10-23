@extends('layouts.publico')
@section('titulo', 'Sobre — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
<section class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-3xl font-extrabold text-marca">Sobre o {{ $assoc->nome ?? 'Pagode da Dona Maria' }}</h1>

    @if (!empty($assoc?->historia))
        <p class="mt-4 text-cafe/80 whitespace-pre-line leading-relaxed">{{ $assoc->historia }}</p>
    @else
        <p class="mt-4 text-cafe/70">A história da associação será publicada em breve. 💜</p>
    @endif

    @if (($assoc->criancas_atendidas ?? 0) || ($assoc->familias_atendidas ?? 0))
    <div class="mt-8 grid grid-cols-2 gap-4">
        @if (($assoc->criancas_atendidas ?? 0) > 0)
        <div class="bg-white rounded-xl2 p-6 text-center shadow-sm">
            <div class="text-4xl font-extrabold text-terracota">{{ $assoc->criancas_atendidas }}</div>
            <div class="text-sm text-cafe/70 mt-1">crianças atendidas</div>
        </div>
        @endif
        @if (($assoc->familias_atendidas ?? 0) > 0)
        <div class="bg-white rounded-xl2 p-6 text-center shadow-sm">
            <div class="text-4xl font-extrabold text-terracota">{{ $assoc->familias_atendidas }}</div>
            <div class="text-sm text-cafe/70 mt-1">famílias atendidas</div>
        </div>
        @endif
    </div>
    @endif

    @if (!empty($assoc?->endereco) || !empty($assoc?->horario_atendimento))
    <div class="mt-8 bg-white rounded-xl2 p-6 shadow-sm space-y-2 text-sm">
        @if (!empty($assoc?->endereco))<p>📍 <strong>Endereço:</strong> {{ $assoc->endereco }}</p>@endif
        @if (!empty($assoc?->horario_atendimento))<p>🕒 <strong>Atendimento:</strong> {{ $assoc->horario_atendimento }}</p>@endif
    </div>
    @endif
</section>
@endsection
