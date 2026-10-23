<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', $assoc->nome ?? 'Pagode da Dona Maria')</title>
    <meta name="description" content="@yield('descricao', 'Ajude a transformar a vida de crianças e famílias. Veja campanhas, necessidades e como você pode ajudar.')">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

    {{-- Fonte --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">

    {{-- Estilos: usa o build do Vite quando existir; senao, cai no CDN (pratico pra demo) --}}
    @php $temBuild = file_exists(public_path('build/manifest.json')); @endphp
    @if ($temBuild)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: { extend: { colors: {
                    marca: { DEFAULT: '#164A36', escuro: '#0F3326', claro: '#1E6B4D' },
                    terracota: { DEFAULT: '#C96F4A', escuro: '#A8562F', claro: '#E08E6B' },
                    creme: '#F4E6D2', manteiga: '#F2C94C', cafe: '#39251F',
                }, fontFamily: { sans: ['Figtree','ui-sans-serif','system-ui','sans-serif'] } } }
            }
        </script>
        <style>
            :root{--cor-primaria:#164A36;--cor-secundaria:#C96F4A;--cor-fundo:#F4E6D2;--cor-destaque:#F2C94C;--cor-texto:#39251F}
            body{font-family:Figtree,ui-sans-serif,system-ui,sans-serif;background:var(--cor-fundo);color:var(--cor-texto)}
            .barra-progresso{width:100%;height:.75rem;border-radius:9999px;background:rgba(0,0,0,.1);overflow:hidden}
            .barra-progresso>span{display:block;height:100%;border-radius:9999px;transition:width .7s ease-out;background:linear-gradient(90deg,var(--cor-secundaria),var(--cor-destaque))}
        </style>
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen flex flex-col antialiased">

    {{-- ===== SPLASH DE ABERTURA (intro em video) ===== --}}
    @includeWhen(!request()->routeIs('login'), 'partials.splash')

    {{-- ===== CABECALHO ===== --}}
    <header x-data="{ aberto: false }" class="sticky top-0 z-40 bg-marca text-white shadow-md">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    @php
                        // Logo enviada pelo painel (storage) tem prioridade; senao usa a oficial em public/img.
                        $logoUrl = !empty($assoc?->logo)
                            ? asset('storage/'.$assoc->logo)
                            : (file_exists(public_path('img/logo.png')) ? asset('img/logo.png') : null);
                    @endphp
                    @if ($logoUrl)
                        {{-- Fundo claro (Opcao A) para a logo verde nao sumir no header verde --}}
                        <span class="inline-flex items-center justify-center h-11 w-11 rounded-full bg-creme p-1 shadow-sm shrink-0">
                            <img src="{{ $logoUrl }}" alt="{{ $assoc->nome ?? 'Pagode da Dona Maria' }}" class="h-full w-full object-contain">
                        </span>
                    @else
                        <span class="text-2xl">💜</span>
                    @endif
                    <span class="font-extrabold text-lg leading-tight">{{ $assoc->nome ?? 'Pagode da Dona Maria' }}</span>
                </a>

                {{-- Menu desktop --}}
                <nav class="hidden md:flex items-center gap-6 text-sm font-semibold">
                    <a href="{{ route('home') }}" class="hover:text-manteiga transition">Início</a>
                    <a href="{{ route('sobre') }}" class="hover:text-manteiga transition">Sobre</a>
                    <a href="{{ route('campanhas.index') }}" class="hover:text-manteiga transition">Campanhas</a>
                    <a href="{{ route('necessidades.index') }}" class="hover:text-manteiga transition">Necessidades</a>
                    <a href="{{ route('como-ajudar') }}" class="rounded-full bg-manteiga text-cafe px-5 py-2 hover:brightness-95 transition">❤️ Quero Ajudar</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-manteiga transition">📊 Painel</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-manteiga transition flex items-center gap-1">🔑 Entrar</a>
                    @endauth
                </nav>

                {{-- Botao hamburguer (mobile) --}}
                <button @click="aberto = !aberto" class="md:hidden p-2 -mr-2" aria-label="Menu">
                    <svg x-show="!aberto" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="aberto" x-cloak class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Menu mobile (drawer) --}}
            <nav x-show="aberto" x-cloak x-transition class="md:hidden pb-4 flex flex-col gap-1 text-sm font-semibold">
                <a href="{{ route('home') }}" class="py-2 px-2 rounded hover:bg-white/10">Início</a>
                <a href="{{ route('sobre') }}" class="py-2 px-2 rounded hover:bg-white/10">Sobre</a>
                <a href="{{ route('campanhas.index') }}" class="py-2 px-2 rounded hover:bg-white/10">Campanhas</a>
                <a href="{{ route('necessidades.index') }}" class="py-2 px-2 rounded hover:bg-white/10">Necessidades</a>
                <a href="{{ route('como-ajudar') }}" class="mt-1 py-2 px-2 rounded-full bg-manteiga text-cafe text-center">❤️ Quero Ajudar</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="mt-1 py-2 px-2 rounded hover:bg-white/10">📊 Painel</a>
                @else
                    <a href="{{ route('login') }}" class="mt-1 py-2 px-2 rounded hover:bg-white/10">🔑 Entrar</a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- ===== CONTEUDO ===== --}}
    <main class="flex-1">
        @yield('conteudo')
    </main>

    {{-- ===== RODAPE ===== --}}
    <footer class="bg-marca-escuro text-white/90 mt-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 grid gap-8 md:grid-cols-3">
            <div>
                <h3 class="font-extrabold text-lg flex items-center gap-2">
                    @if ($logoUrl ?? false)
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-creme p-0.5">
                            <img src="{{ $logoUrl }}" alt="" class="h-full w-full object-contain">
                        </span>
                    @else
                        💜
                    @endif
                    {{ $assoc->nome ?? 'Pagode da Dona Maria' }}
                </h3>
                <p class="mt-2 text-sm text-white/70">{{ $assoc->sobre_trabalho ?? 'Ajudando crianças e famílias da comunidade.' }}</p>
            </div>
            <div class="text-sm space-y-1">
                <h4 class="font-bold mb-2">Contato</h4>
                @if (!empty($assoc?->endereco))<p>📍 {{ $assoc->endereco }}</p>@endif
                @if (!empty($assoc?->telefone))<p>📞 {{ $assoc->telefone }}</p>@endif
                @if (!empty($assoc?->whatsapp))
                    <p><a class="hover:text-manteiga" target="_blank" href="https://wa.me/{{ preg_replace('/\D/', '', $assoc->whatsapp) }}">💬 WhatsApp</a></p>
                @endif
            </div>
            <div class="text-sm space-y-1">
                <h4 class="font-bold mb-2">Redes</h4>
                @if (!empty($assoc?->instagram))<p><a class="hover:text-manteiga" target="_blank" href="https://instagram.com/{{ ltrim($assoc->instagram,'@') }}">📷 Instagram</a></p>@endif
                @if (!empty($assoc?->facebook))<p><a class="hover:text-manteiga" target="_blank" href="{{ $assoc->facebook }}">📘 Facebook</a></p>@endif
                <p class="pt-3 space-y-1">
                    <a href="{{ route('portal.entrar') }}" class="block text-white/50 hover:text-white/80 text-xs">👪 Portal do Responsável →</a>
                    <a href="{{ route('login') }}" class="block text-white/50 hover:text-white/80 text-xs">Acesso da equipe →</a>
                </p>
            </div>
        </div>
        <div class="border-t border-white/10 py-4 text-center text-xs text-white/50">
            © {{ date('Y') }} {{ $assoc->nome ?? 'Pagode da Dona Maria' }} · Feito com 💜 para a comunidade
        </div>
    </footer>

    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
