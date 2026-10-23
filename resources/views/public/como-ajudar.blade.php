@extends('layouts.publico')
@section('titulo', 'Como Ajudar — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
<section class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-3xl font-extrabold text-marca text-center">Como você pode ajudar 💜</h1>
    <p class="mt-2 text-cafe/70 text-center">Escolha a melhor forma pra você. Toda ajuda transforma vidas.</p>

    {{-- Pix --}}
    <div class="mt-10 bg-white rounded-xl2 shadow-sm p-6 md:p-8">
        <h2 class="text-xl font-bold text-marca flex items-center gap-2">❤️ Doar em dinheiro (Pix)</h2>
        @if (!empty($assoc?->pix_chave))
            <div class="mt-4 grid md:grid-cols-2 gap-6 items-center">
                <div>
                    <p class="text-sm text-cafe/70">Chave Pix ({{ $assoc->pix_tipo ?? 'chave' }}):</p>
                    <div x-data="{ copiado:false }" class="mt-1 flex items-center gap-2">
                        <code class="bg-creme px-3 py-2 rounded-lg font-mono text-sm break-all">{{ $assoc->pix_chave }}</code>
                        <button @click="navigator.clipboard.writeText('{{ $assoc->pix_chave }}'); copiado=true; setTimeout(()=>copiado=false,2000)"
                                class="rounded-lg bg-marca text-white text-sm px-3 py-2">
                            <span x-show="!copiado">Copiar</span><span x-show="copiado" x-cloak>✓ Copiado</span>
                        </button>
                    </div>
                    @if (!empty($assoc?->pix_nome_recebedor))
                        <p class="mt-3 text-xs text-cafe/60">Recebedor: {{ $assoc->pix_nome_recebedor }}</p>
                    @endif
                </div>
                <div class="flex justify-center">
                    {{-- QR Code gerado no servidor (SimpleSoftwareIO) — placeholder ate configurar --}}
                    <div class="h-40 w-40 bg-creme rounded-xl2 flex items-center justify-center text-cafe/40 text-sm text-center p-4">
                        QR Code Pix<br>(gerado ao configurar)
                    </div>
                </div>
            </div>
        @else
            <p class="mt-3 text-cafe/60 text-sm">A chave Pix ainda será configurada. Entre em contato pelo WhatsApp para doar. 💜</p>
        @endif
    </div>

    {{-- Doacoes fisicas + voluntario --}}
    <div class="mt-6 grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl2 shadow-sm p-6">
            <h2 class="text-xl font-bold text-marca">📦 Doar itens</h2>
            <p class="mt-2 text-sm text-cafe/70">Aceitamos alimentos, roupas, calçados, brinquedos, material escolar, produtos de higiene e muito mais.</p>
            @if (!empty($assoc?->whatsapp))
                <a target="_blank" href="https://wa.me/{{ preg_replace('/\D/','',$assoc->whatsapp) }}?text={{ rawurlencode('Olá! Gostaria de doar itens para a associação.') }}"
                   class="mt-4 inline-flex items-center gap-2 rounded-full bg-green-500 text-white font-semibold px-5 py-2.5 hover:brightness-95 transition">
                    💬 Combinar pelo WhatsApp
                </a>
            @endif
        </div>
        <div class="bg-white rounded-xl2 shadow-sm p-6">
            <h2 class="text-xl font-bold text-marca">🚗 Ser voluntário</h2>
            <p class="mt-2 text-sm text-cafe/70">Doe seu tempo e talento. Precisamos de ajuda em arrecadação, transporte, organização, eventos e mais.</p>
            <a href="{{ route('voluntario.form') }}" class="mt-4 inline-flex btn-terracota">Quero ser voluntário</a>
        </div>
    </div>
</section>
@endsection
