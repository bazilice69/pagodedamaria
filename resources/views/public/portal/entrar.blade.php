@extends('layouts.publico')
@section('titulo', 'Portal do Responsável — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
<section class="max-w-md mx-auto px-4 sm:px-6 py-16">
    <div class="bg-white rounded-xl2 shadow-md p-8">
        <div class="text-center">
            <span class="text-4xl">👪</span>
            <h1 class="mt-2 text-2xl font-extrabold text-marca">Portal do Responsável</h1>
            <p class="text-sm text-cafe/60">Acompanhe as atividades do seu filho(a)</p>
        </div>

        @if (session('erro'))
            <div class="mt-4 bg-red-100 text-red-700 rounded-xl p-3 text-sm">{{ session('erro') }}</div>
        @endif

        <form method="POST" action="{{ route('portal.entrar.submit') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold mb-1">Código do aluno</label>
                <input name="codigo" value="{{ old('codigo') }}" required placeholder="Ex: PDM-000003 (ou só 3)" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                <p class="text-xs text-cafe/50 mt-1">O código está na carteirinha. Em dúvida, peça à coordenação.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Data de nascimento do aluno</label>
                <input type="date" name="nascimento" value="{{ old('nascimento') }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <button class="btn-marca w-full">Entrar</button>
        </form>
    </div>
    <p class="text-center mt-4"><a href="{{ route('home') }}" class="text-sm text-cafe/60 hover:text-marca">← voltar ao site</a></p>
</section>
@endsection
