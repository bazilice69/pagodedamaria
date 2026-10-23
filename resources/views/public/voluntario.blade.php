@extends('layouts.publico')
@section('titulo', 'Seja Voluntário — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
<section class="max-w-2xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-3xl font-extrabold text-marca">🚗 Quero ser voluntário</h1>
    <p class="mt-2 text-cafe/70">Preencha e entraremos em contato. Obrigado por querer ajudar! 💜</p>

    @if (session('sucesso'))
        <div class="mt-6 bg-green-100 text-green-800 rounded-xl2 p-4 font-semibold">{{ session('sucesso') }}</div>
    @endif

    <form method="POST" action="{{ route('voluntario.store') }}" class="mt-6 bg-white rounded-xl2 shadow-sm p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-semibold mb-1">Nome *</label>
            <input name="nome" value="{{ old('nome') }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            @error('nome')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">WhatsApp</label>
                <input name="whatsapp" value="{{ old('whatsapp') }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">E-mail</label>
                <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-2">Em que você pode ajudar?</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach ($areas as $valor => $rotulo)
                    <label class="flex items-center gap-2 text-sm bg-creme rounded-lg px-3 py-2 cursor-pointer">
                        <input type="checkbox" name="areas[]" value="{{ $valor }}" class="rounded text-marca focus:ring-marca">
                        {{ $rotulo }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Disponibilidade</label>
                <input name="disponibilidade" value="{{ old('disponibilidade') }}" placeholder="Ex: fins de semana" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Região</label>
                <input name="regiao" value="{{ old('regiao') }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
        </div>
        <button class="btn-marca w-full">Enviar cadastro</button>
    </form>
</section>
@endsection
