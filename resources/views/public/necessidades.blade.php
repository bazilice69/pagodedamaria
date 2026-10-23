@extends('layouts.publico')
@section('titulo', 'Necessidades — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-3xl font-extrabold text-marca">🚨 O que estamos precisando</h1>
    <p class="mt-2 text-cafe/70">Veja as necessidades atuais e ajude com o que puder. 💜</p>

    @if ($necessidades->isEmpty())
        <div class="mt-10 bg-white rounded-xl2 p-10 text-center text-cafe/60">
            No momento não há necessidades cadastradas. Volte em breve!
        </div>
    @else
        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($necessidades as $n)
                @include('public.partials.card-necessidade', ['n' => $n])
            @endforeach
        </div>
        <div class="mt-8">{{ $necessidades->links() }}</div>
    @endif
</section>
@endsection
