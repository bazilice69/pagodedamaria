@extends('layouts.publico')
@section('titulo', 'Acesso da equipe — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
<section class="max-w-md mx-auto px-4 sm:px-6 py-16">
    <div class="bg-white rounded-xl2 shadow-md p-8">
        <div class="text-center">
            <span class="text-4xl">💜</span>
            <h1 class="mt-2 text-2xl font-extrabold text-marca">Acesso da equipe</h1>
            <p class="text-sm text-cafe/60">{{ $assoc->nome ?? 'Pagode da Dona Maria' }}</p>
        </div>

        <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold mb-1">E-mail</label>
                <input name="email" type="email" value="{{ old('email') }}" required autofocus
                       class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Senha</label>
                <input name="password" type="password" required
                       class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" class="rounded text-marca focus:ring-marca"> Continuar conectado
            </label>
            <button class="btn-marca w-full">Entrar</button>
        </form>
    </div>
    <p class="text-center mt-4"><a href="{{ route('home') }}" class="text-sm text-cafe/60 hover:text-marca">← voltar ao site</a></p>
</section>
@endsection
