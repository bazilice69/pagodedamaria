@extends('layouts.admin')
@section('titulo', $pessoa->nome)

@section('conteudo')
<a href="{{ route('admin.pessoas.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>

<div class="mt-2 bg-white rounded-xl2 shadow-sm p-6">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        @if ($pessoa->foto)
            <img src="{{ asset('storage/'.$pessoa->foto) }}" class="h-24 w-24 rounded-full object-cover">
        @else
            <span class="h-24 w-24 rounded-full bg-creme flex items-center justify-center text-3xl">👤</span>
        @endif
        <div class="flex-1">
            <h1 class="text-2xl font-extrabold text-marca">{{ $pessoa->nome }}</h1>
            <p class="text-cafe/60 text-sm">{{ $pessoa->codigo }} @if($pessoa->idade !== null)· {{ $pessoa->idade }} anos @endif</p>
            <div class="flex flex-wrap gap-1 mt-2">
                @foreach ($pessoa->papeis as $papelLabel)<span class="text-xs bg-creme px-2 py-0.5 rounded-full">{{ $papelLabel }}</span>@endforeach
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pessoas.carteirinha', $pessoa) }}" target="_blank" class="btn-marca text-sm">🪪 Carteirinha</a>
            <a href="{{ route('admin.pessoas.edit', $pessoa) }}" class="px-4 py-2 rounded-full text-marca font-semibold hover:bg-creme text-sm self-center">editar</a>
        </div>
    </div>
</div>

<div class="mt-6 grid lg:grid-cols-2 gap-6">
    {{-- Contato e endereco --}}
    <div class="bg-white rounded-xl2 shadow-sm p-6 text-sm space-y-1">
        <h2 class="font-bold text-marca mb-2">Contato</h2>
        @if ($pessoa->whatsapp)<p>💬 <a target="_blank" href="https://wa.me/{{ preg_replace('/\D/','',$pessoa->whatsapp) }}" class="hover:underline">{{ $pessoa->whatsapp }}</a></p>@endif
        @if ($pessoa->telefone)<p>📞 {{ $pessoa->telefone }}</p>@endif
        @if ($pessoa->email)<p>✉️ {{ $pessoa->email }}</p>@endif
        @if ($pessoa->endereco_completo)<p class="pt-1">📍 {{ $pessoa->endereco_completo }}</p>@endif
        <p class="pt-2 text-xs {{ $pessoa->autoriza_imagem ? 'text-green-600' : 'text-cafe/50' }}">
            📸 {{ $pessoa->autoriza_imagem ? 'Autoriza uso de imagem' : 'NÃO autoriza uso de imagem' }}
        </p>
    </div>

    {{-- Beneficios --}}
    <div class="bg-white rounded-xl2 shadow-sm p-6 text-sm">
        <h2 class="font-bold text-marca mb-2">🧾 Benefícios sociais</h2>
        @if ($pessoa->beneficios->isEmpty())
            <p class="text-cafe/50">Nenhum benefício informado.</p>
        @else
            <ul class="space-y-1">
                @foreach ($pessoa->beneficios as $b)
                    <li class="flex justify-between">
                        <span>{{ $b->nome }}</span>
                        @if ($b->valor)<span class="text-cafe/60">R$ {{ number_format($b->valor,2,',','.') }}</span>@endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Cursos --}}
    <div class="bg-white rounded-xl2 shadow-sm p-6 text-sm">
        <h2 class="font-bold text-marca mb-2">🎓 Cursos matriculados</h2>
        @if ($pessoa->cursos->isEmpty())
            <p class="text-cafe/50">Não está matriculado em nenhum curso.</p>
        @else
            <ul class="space-y-1">
                @foreach ($pessoa->cursos as $c)<li>• {{ $c->nome }}</li>@endforeach
            </ul>
        @endif
    </div>

    {{-- Dependentes --}}
    @if ($pessoa->dependentes->isNotEmpty())
    <div class="bg-white rounded-xl2 shadow-sm p-6 text-sm">
        <h2 class="font-bold text-marca mb-2">👪 Dependentes</h2>
        <ul class="space-y-1">
            @foreach ($pessoa->dependentes as $d)
                <li><a href="{{ route('admin.pessoas.show', $d) }}" class="hover:underline">• {{ $d->nome }}</a></li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

@if ($pessoa->observacao)
<div class="mt-6 bg-white rounded-xl2 shadow-sm p-6 text-sm">
    <h2 class="font-bold text-marca mb-2">Observações</h2>
    <p class="whitespace-pre-line text-cafe/80">{{ $pessoa->observacao }}</p>
</div>
@endif
@endsection
