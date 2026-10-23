@extends('layouts.publico')
@section('titulo', 'Painel do Responsável — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
<section class="max-w-2xl mx-auto px-4 sm:px-6 py-12">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-marca">👋 Olá!</h1>
        <form method="POST" action="{{ route('portal.sair') }}">@csrf<button class="text-sm text-cafe/60 hover:text-marca">sair</button></form>
    </div>

    {{-- Card do aluno --}}
    <div class="mt-4 bg-white rounded-xl2 shadow-sm p-6 flex items-center gap-4">
        @if ($pessoa->foto && $pessoa->autoriza_imagem)
            <img src="{{ asset('storage/'.$pessoa->foto) }}" class="h-16 w-16 rounded-full object-cover">
        @else
            <span class="h-16 w-16 rounded-full bg-creme flex items-center justify-center text-2xl">🧒</span>
        @endif
        <div>
            <div class="font-bold text-marca text-lg">{{ $pessoa->nome }}</div>
            <div class="text-sm text-cafe/60">{{ $pessoa->codigo }} @if($pessoa->idade !== null)· {{ $pessoa->idade }} anos @endif</div>
        </div>
    </div>

    {{-- Cursos e frequencia --}}
    <h2 class="mt-8 font-bold text-marca">🎓 Atividades e presença</h2>
    @if (empty($frequencia))
        <div class="mt-3 bg-white rounded-xl2 shadow-sm p-8 text-center text-cafe/50">
            {{ $pessoa->nome }} ainda não está matriculado(a) em nenhuma atividade.
        </div>
    @else
        <div class="mt-3 space-y-3">
            @foreach ($frequencia as $f)
                <div class="bg-white rounded-xl2 shadow-sm p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-bold text-marca">{{ $f['curso']->categoria?->icone }} {{ $f['curso']->nome }}</div>
                            <div class="text-xs text-cafe/60">{{ $f['curso']->dias_semana }} {{ $f['curso']->horario }}</div>
                        </div>
                        @if ($f['pct'] !== null)
                            <span class="font-bold {{ $f['pct'] >= 75 ? 'text-green-600' : ($f['pct'] >= 50 ? 'text-orange-500' : 'text-red-500') }}">{{ $f['pct'] }}%</span>
                        @endif
                    </div>
                    @if ($f['total'] > 0)
                        <p class="text-sm text-cafe/60 mt-1">Presente em {{ $f['presentes'] }} de {{ $f['total'] }} aulas</p>
                    @else
                        <p class="text-sm text-cafe/50 mt-1">Ainda sem chamadas registradas</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <p class="mt-8 text-xs text-cafe/50 text-center">
        Dúvidas? Fale com a coordenação @if($assoc->whatsapp ?? false) pelo WhatsApp {{ $assoc->whatsapp }}@endif.
    </p>
</section>
@endsection
