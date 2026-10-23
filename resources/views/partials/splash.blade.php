{{--
| SPLASH DE ABERTURA — intro em video da Dona Maria animada.
|
| Comportamento:
|  - So aparece se o arquivo public/video/intro.mp4 existir (ate la, nada quebra).
|  - Mostra apenas 1x por navegador (guarda em localStorage). Link "rever" reabre.
|  - Comeca MUTADO (regra dos navegadores) com botao para ligar o som (o pagode).
|  - Botao "Pular / Entrar" sempre visivel. Fecha sozinho quando o video acaba.
|  - Responsivo (PC e celular), video cobrindo a tela (object-cover).
--}}
@php $temIntro = file_exists(public_path('video/intro.mp4')); @endphp

@if ($temIntro)
<div
    x-data="splashIntro()"
    x-show="visivel"
    x-cloak
    class="fixed inset-0 z-[100] bg-marca-escuro flex items-center justify-center"
    @keydown.escape.window="pular()"
>
    {{-- Video --}}
    <video
        x-ref="video"
        class="absolute inset-0 w-full h-full object-cover"
        playsinline
        muted
        autoplay
        @ended="fechar()"
        @if (file_exists(public_path('img/intro-poster.jpg'))) poster="{{ asset('img/intro-poster.jpg') }}" @endif
    >
        <source src="{{ asset('video/intro.mp4') }}" type="video/mp4">
    </video>

    {{-- Camada escura suave pra leitura dos botoes --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/20 pointer-events-none"></div>

    {{-- Controles --}}
    <div class="absolute bottom-6 inset-x-0 px-6 flex items-center justify-between gap-4">
        {{-- Ligar/desligar som (o pagode) --}}
        <button
            @click="alternarSom()"
            class="flex items-center gap-2 rounded-full bg-white/90 text-cafe px-4 py-2 text-sm font-semibold shadow hover:bg-white transition"
        >
            <span x-show="semSom">🔊 Tocar o pagode</span>
            <span x-show="!semSom" x-cloak>🔈 Silenciar</span>
        </button>

        {{-- Pular / Entrar --}}
        <button
            @click="pular()"
            class="flex items-center gap-2 rounded-full bg-manteiga text-cafe px-6 py-2 text-sm font-bold shadow hover:brightness-95 transition"
        >
            Pular intro →
        </button>
    </div>
</div>

<script>
    function splashIntro() {
        return {
            // Mostra so na 1a visita desta sessao de navegador.
            visivel: ! sessionStorage.getItem('intro_vista'),
            semSom: true,
            init() {
                if (! this.visivel) return;
                // Tenta tocar; se o navegador bloquear, segue mutado mesmo.
                this.$nextTick(() => {
                    const v = this.$refs.video;
                    if (v) { v.muted = true; v.play().catch(() => {}); }
                });
            },
            alternarSom() {
                const v = this.$refs.video;
                if (! v) return;
                v.muted = ! v.muted;
                this.semSom = v.muted;
                if (! v.muted) v.play().catch(() => {});
            },
            pular() { this.fechar(); },
            fechar() {
                const v = this.$refs.video;
                if (v) v.pause();
                this.visivel = false;
                sessionStorage.setItem('intro_vista', '1');
            },
        }
    }
</script>
@endif
