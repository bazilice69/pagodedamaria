@extends('layouts.publico')
@section('titulo', 'Contato — '.($assoc->nome ?? 'Pagode da Dona Maria'))

@section('conteudo')
<section class="max-w-2xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-3xl font-extrabold text-marca">Fale com a gente</h1>
    <div class="mt-6 bg-white rounded-xl2 p-6 shadow-sm space-y-3 text-sm">
        @if (!empty($assoc?->whatsapp))
            <a target="_blank" href="https://wa.me/{{ preg_replace('/\D/','',$assoc->whatsapp) }}" class="flex items-center gap-3 hover:text-terracota">💬 <span>WhatsApp: {{ $assoc->whatsapp }}</span></a>
        @endif
        @if (!empty($assoc?->telefone))<p class="flex items-center gap-3">📞 <span>{{ $assoc->telefone }}</span></p>@endif
        @if (!empty($assoc?->email))<p class="flex items-center gap-3">✉️ <span>{{ $assoc->email }}</span></p>@endif
        @if (!empty($assoc?->instagram))<a target="_blank" href="https://instagram.com/{{ ltrim($assoc->instagram,'@') }}" class="flex items-center gap-3 hover:text-terracota">📷 <span>{{ $assoc->instagram }}</span></a>@endif
        @if (!empty($assoc?->endereco))<p class="flex items-center gap-3">📍 <span>{{ $assoc->endereco }}</span></p>@endif
    </div>
</section>
@endsection
