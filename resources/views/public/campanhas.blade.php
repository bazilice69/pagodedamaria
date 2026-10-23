@extends('layouts.publico')
@section('titulo', 'Campanhas — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-3xl font-extrabold text-marca">🎯 Campanhas</h1>
    <p class="mt-2 text-cafe/70">Participe das nossas campanhas e ajude a alcançar as metas.</p>

    @if ($campanhas->isEmpty())
        <div class="mt-10 bg-white rounded-xl2 p-10 text-center text-cafe/60">
            Nenhuma campanha ativa no momento.
        </div>
    @else
        <div class="mt-8 grid gap-6 md:grid-cols-3">
            @foreach ($campanhas as $c)
                @include('public.partials.card-campanha', ['c' => $c])
            @endforeach
        </div>
        <div class="mt-8">{{ $campanhas->links() }}</div>
    @endif
</section>
@endsection
