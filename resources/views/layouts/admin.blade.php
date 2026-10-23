<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Painel') · {{ $assoc->nome ?? 'Pagode da Dona Maria' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @php $temBuild = file_exists(public_path('build/manifest.json')); @endphp
    @if ($temBuild)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { theme: { extend: { colors: {
                marca: { DEFAULT: '#164A36', escuro: '#0F3326', claro: '#1E6B4D' },
                terracota: { DEFAULT: '#C96F4A', escuro: '#A8562F', claro: '#E08E6B' },
                creme: '#F4E6D2', manteiga: '#F2C94C', cafe: '#39251F',
            }, fontFamily: { sans: ['Figtree','ui-sans-serif','system-ui','sans-serif'] } } } }
        </script>
        <style>body{font-family:Figtree,ui-sans-serif,system-ui,sans-serif}
        .barra-progresso{width:100%;height:.75rem;border-radius:9999px;background:rgba(0,0,0,.1);overflow:hidden}
        .barra-progresso>span{display:block;height:100%;border-radius:9999px;background:linear-gradient(90deg,#C96F4A,#F2C94C)}
        .btn-marca{display:inline-flex;align-items:center;gap:.5rem;justify-content:center;border-radius:9999px;padding:.6rem 1.25rem;font-weight:600;color:#fff;background:#164A36}
        [x-cloak]{display:none!important}</style>
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-creme text-cafe antialiased min-h-screen" x-data="{ menu: false }">

    @php
        $itens = [
            ['admin.dashboard', '📊', 'Painel', []],
            ['admin.doacoes.index', '❤️', 'Doações', ['admin.doacoes.*']],
            ['admin.campanhas.index', '🎯', 'Campanhas', ['admin.campanhas.*']],
            ['admin.necessidades.index', '🚨', 'Necessidades', ['admin.necessidades.*']],
            ['admin.estoque.index', '📦', 'Estoque', ['admin.estoque.*']],
        ];
        $itensAdmin = [
            ['admin.voluntarios.index', '🚗', 'Voluntários', ['admin.voluntarios.*']],
            ['admin.configuracoes.edit', '⚙️', 'Configurações', ['admin.configuracoes.*']],
        ];
    @endphp

    {{-- Topbar mobile --}}
    <div class="md:hidden sticky top-0 z-40 bg-marca text-white flex items-center justify-between px-4 h-14">
        <span class="font-extrabold">💜 {{ $assoc->nome ?? 'Pagode' }}</span>
        <button @click="menu = !menu" class="p-2 -mr-2" aria-label="Menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div class="flex">
        {{-- Sidebar / Drawer --}}
        <aside
            class="fixed md:sticky top-0 z-50 md:z-0 h-screen w-64 bg-marca text-white flex flex-col transition-transform md:translate-x-0"
            :class="menu ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="h-16 flex items-center gap-2 px-5 border-b border-white/10">
                <span class="text-2xl">💜</span>
                <span class="font-extrabold leading-tight">{{ $assoc->nome ?? 'Pagode da Dona Maria' }}</span>
            </div>
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 text-sm">
                @foreach ($itens as [$rota, $icone, $label, $ativo])
                    <a href="{{ route($rota) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs($rota) || (count($ativo) && request()->routeIs($ativo)) ? 'bg-white/15 font-semibold' : 'hover:bg-white/10' }}">
                        <span>{{ $icone }}</span> {{ $label }}
                    </a>
                @endforeach

                @if (auth()->user()?->isAdmin())
                    <div class="pt-3 mt-3 border-t border-white/10 text-white/50 text-xs px-3 pb-1">Administração</div>
                    @foreach ($itensAdmin as [$rota, $icone, $label, $ativo])
                        <a href="{{ route($rota) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs($ativo) ? 'bg-white/15 font-semibold' : 'hover:bg-white/10' }}">
                            <span>{{ $icone }}</span> {{ $label }}
                        </a>
                    @endforeach
                @endif
            </nav>
            <div class="p-3 border-t border-white/10 text-sm">
                <div class="px-3 py-2 text-white/70 truncate">👤 {{ auth()->user()?->name }}</div>
                <a href="{{ route('home') }}" target="_blank" class="block px-3 py-2 rounded-lg hover:bg-white/10">🌐 Ver o site</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-3 py-2 rounded-lg hover:bg-white/10">🚪 Sair</button>
                </form>
            </div>
        </aside>

        {{-- Overlay mobile --}}
        <div x-show="menu" x-cloak @click="menu = false" class="fixed inset-0 bg-black/40 z-40 md:hidden"></div>

        {{-- Conteudo --}}
        <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8">
            @if (session('sucesso'))
                <div class="mb-4 bg-green-100 text-green-800 rounded-xl px-4 py-3 font-semibold">{{ session('sucesso') }}</div>
            @endif
            @yield('conteudo')
        </main>
    </div>
</body>
</html>
