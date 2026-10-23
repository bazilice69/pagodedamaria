@extends('layouts.admin')
@section('titulo', 'Equipe')

@section('conteudo')
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">🏛️ Equipe e Cargos</h1>
        <p class="text-cafe/60 text-sm mt-1">Diretoria, professores e voluntários com acesso ao sistema.</p>
    </div>
    <a href="{{ route('admin.equipe.create') }}" class="btn-marca shrink-0">+ Novo membro</a>
</div>

<div class="bg-white rounded-xl2 shadow-sm overflow-hidden">
    <div class="divide-y divide-creme">
        @foreach ($membros as $m)
            @php
                $corCargo = match($m->role) {
                    'presidente', 'admin' => 'bg-manteiga text-cafe',
                    'vice' => 'bg-terracota/20 text-terracota',
                    'diretor' => 'bg-marca/10 text-marca',
                    'professor' => 'bg-blue-100 text-blue-700',
                    default => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <div class="flex items-center gap-4 p-4">
                <span class="h-11 w-11 rounded-full bg-creme flex items-center justify-center text-lg shrink-0">
                    {{ $m->isPresidente() ? '👑' : '👤' }}
                </span>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-marca truncate">{{ $m->name }} @if(!$m->ativo)<span class="text-xs text-red-500">(inativo)</span>@endif</div>
                    <div class="text-xs text-cafe/60">{{ $m->email }}</div>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $corCargo }}">{{ $m->cargo_exibicao }}</span>
                @unless ($m->isPresidente())
                    <a href="{{ route('admin.equipe.edit', $m) }}" class="text-marca text-sm font-semibold hover:underline">editar</a>
                @endunless
            </div>
        @endforeach
    </div>
</div>

{{-- Transferir presidencia --}}
<div class="mt-8 bg-white rounded-xl2 shadow-sm p-6 border-2 border-manteiga/40" x-data="{ aberto: false }">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-bold text-marca flex items-center gap-2">👑 Transferir presidência</h2>
            <p class="text-sm text-cafe/60 mt-1">
                Presidente atual: <strong>{{ $presidente?->name ?? '—' }}</strong>.
                Ao transferir, você passa a ser Vice-Presidente automaticamente.
            </p>
        </div>
        <button @click="aberto = !aberto" class="text-sm text-terracota font-semibold shrink-0">alterar</button>
    </div>

    <form x-show="aberto" x-cloak method="POST" action="{{ route('admin.equipe.transferir') }}" class="mt-4 space-y-3"
          onsubmit="return confirm('Tem certeza? Você deixará de ser presidente e virará Vice.')">
        @csrf
        <div>
            <label class="block text-sm font-semibold mb-1">Novo presidente</label>
            <select name="novo_presidente_id" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                <option value="">Selecione um membro...</option>
                @foreach ($membros as $m)
                    @unless ($m->isPresidente())
                        <option value="{{ $m->id }}">{{ $m->name }} — {{ $m->cargo_exibicao }}</option>
                    @endunless
                @endforeach
            </select>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="confirmar" value="1" required class="rounded text-marca focus:ring-marca">
            Confirmo que quero transferir a presidência
        </label>
        <button class="rounded-full bg-manteiga text-cafe font-bold px-6 py-2.5 hover:brightness-95">👑 Transferir presidência</button>
    </form>
</div>
@endsection
