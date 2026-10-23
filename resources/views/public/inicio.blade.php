@extends('layouts.publico')

@section('titulo', ($assoc->nome ?? 'Pagode da Dona Maria').' — Ajude você também')

@section('conteudo')

{{-- ================= HERO ================= --}}
<section class="relative overflow-hidden bg-marca text-white">
    {{-- Fundo: animacao da Dona Maria em loop (aparece se o video existir) --}}
    @php $temHero = file_exists(public_path('video/intro.mp4')); @endphp
    @if ($temHero)
        <video class="absolute inset-0 w-full h-full object-cover opacity-30"
               autoplay muted loop playsinline
               @if (file_exists(public_path('img/intro-poster.jpg'))) poster="{{ asset('img/intro-poster.jpg') }}" @endif>
            <source src="{{ asset('video/intro.mp4') }}" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-marca/60"></div>
    @endif

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-20 md:py-28 text-center">
        @php
            $logoUrl = !empty($assoc?->logo)
                ? asset('storage/'.$assoc->logo)
                : (file_exists(public_path('img/logo.png')) ? asset('img/logo.png') : null);
        @endphp
        @if ($logoUrl)
            {{-- Logo em destaque numa "medalha" clara para contrastar com o fundo verde --}}
            <span class="inline-flex items-center justify-center h-28 w-28 md:h-32 md:w-32 rounded-full bg-creme p-3 shadow-lg mb-6">
                <img src="{{ $logoUrl }}" alt="{{ $assoc->nome ?? 'Pagode da Dona Maria' }}" class="h-full w-full object-contain">
            </span>
            {{-- Nome ja aparece na logo; mantemos como texto acessivel (leitores de tela) --}}
            <h1 class="sr-only">{{ $assoc->nome ?? 'Pagode da Dona Maria' }}</h1>
        @else
            <span class="inline-block text-5xl mb-4">💜</span>
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">
                {{ $assoc->nome ?? 'Pagode da Dona Maria' }}
            </h1>
        @endif
        <p class="mt-4 text-lg md:text-xl text-white/90 max-w-2xl mx-auto">
            {{ $assoc->sobre_trabalho ?? 'Juntos, transformamos a vida de crianças e famílias da nossa comunidade.' }}
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('como-ajudar') }}" class="rounded-full bg-manteiga text-cafe font-bold px-8 py-3 shadow hover:brightness-95 transition">
                ❤️ Quero Ajudar
            </a>
            <a href="{{ route('necessidades.index') }}" class="rounded-full bg-white/15 backdrop-blur text-white font-semibold px-8 py-3 hover:bg-white/25 transition">
                📦 O que estamos precisando
            </a>
        </div>

        {{-- Numeros de impacto (SEM expor dados de criancas — so agregados) --}}
        @if (($assoc->criancas_atendidas ?? 0) || ($assoc->familias_atendidas ?? 0))
        <div class="mt-12 flex flex-wrap justify-center gap-8 text-center">
            @if (($assoc->criancas_atendidas ?? 0) > 0)
            <div><div class="text-3xl font-extrabold text-manteiga">{{ $assoc->criancas_atendidas }}</div><div class="text-sm text-white/80">crianças atendidas</div></div>
            @endif
            @if (($assoc->familias_atendidas ?? 0) > 0)
            <div><div class="text-3xl font-extrabold text-manteiga">{{ $assoc->familias_atendidas }}</div><div class="text-sm text-white/80">famílias atendidas</div></div>
            @endif
        </div>
        @endif
    </div>
</section>

{{-- ================= NECESSIDADES URGENTES ================= --}}
@if ($necessidades->isNotEmpty())
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-14">
    <div class="flex items-end justify-between mb-6">
        <h2 class="text-2xl md:text-3xl font-extrabold text-marca">🚨 Estamos precisando</h2>
        <a href="{{ route('necessidades.index') }}" class="text-terracota font-semibold text-sm hover:underline">ver todas →</a>
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
        @foreach ($necessidades as $n)
            @include('public.partials.card-necessidade', ['n' => $n])
        @endforeach
    </div>
</section>
@endif

{{-- ================= CAMPANHAS ================= --}}
@if ($campanhas->isNotEmpty())
<section class="bg-white/50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-14">
        <div class="flex items-end justify-between mb-6">
            <h2 class="text-2xl md:text-3xl font-extrabold text-marca">🎯 Campanhas</h2>
            <a href="{{ route('campanhas.index') }}" class="text-terracota font-semibold text-sm hover:underline">ver todas →</a>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($campanhas as $c)
                @include('public.partials.card-campanha', ['c' => $c])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================= NOSSOS PARCEIROS ================= --}}
@if (isset($parceiros) && $parceiros->isNotEmpty())
<section class="bg-white/50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-14 text-center">
        <h2 class="text-2xl md:text-3xl font-extrabold text-marca">🤝 Nossos Parceiros</h2>
        <p class="mt-2 text-cafe/70">Comércios e amigos que apoiam o nosso trabalho. Obrigado! 💜</p>
        <div class="mt-8 flex flex-wrap justify-center gap-5">
            @foreach ($parceiros as $p)
                @php $href = $p->site ? (str_starts_with($p->site, '@') ? 'https://instagram.com/'.ltrim($p->site,'@') : $p->site) : null; @endphp
                <a @if($href) href="{{ $href }}" target="_blank" @endif
                   class="bg-white rounded-xl2 shadow-sm hover:shadow-md hover:-translate-y-1 transition p-5 w-40 flex flex-col items-center gap-2">
                    @if ($p->logo)
                        <img src="{{ asset('storage/'.$p->logo) }}" alt="{{ $p->nome }}" class="h-16 w-16 object-contain">
                    @else
                        <span class="h-16 w-16 flex items-center justify-center text-3xl bg-creme rounded-lg">🏪</span>
                    @endif
                    <span class="font-semibold text-marca text-sm">{{ $p->nome }}</span>
                    @if ($p->categoria)<span class="text-xs text-cafe/50">{{ $p->categoria }}</span>@endif
                </a>
            @endforeach
        </div>
        <p class="mt-6 text-sm text-cafe/60">Quer ser nosso parceiro? <a href="{{ route('contato') }}" class="text-terracota font-semibold hover:underline">Fale com a gente</a></p>
    </div>
</section>
@endif

{{-- ================= COMO AJUDAR ================= --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-16 text-center">
    <h2 class="text-2xl md:text-3xl font-extrabold text-marca">Como você pode ajudar?</h2>
    <p class="mt-2 text-cafe/70">Toda ajuda faz diferença 💜</p>
    <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
        @foreach ([
            ['❤️','Doar dinheiro'], ['🍚','Alimentos'], ['🧸','Brinquedos'],
            ['👕','Roupas'], ['🎒','Material escolar'], ['🚗','Ser voluntário'],
        ] as [$emoji, $label])
        <a href="{{ route('como-ajudar') }}" class="rounded-xl bg-white shadow-sm hover:shadow-md hover:-translate-y-1 transition p-5 flex flex-col items-center gap-2">
            <span class="text-3xl">{{ $emoji }}</span>
            <span class="text-sm font-semibold text-cafe">{{ $label }}</span>
        </a>
        @endforeach
    </div>
</section>

@endsection
