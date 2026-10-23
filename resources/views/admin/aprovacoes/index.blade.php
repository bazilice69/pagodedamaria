@extends('layouts.admin')
@section('titulo', 'Aprovações')

@section('conteudo')
<h1 class="text-2xl md:text-3xl font-extrabold text-marca">🔔 Aprovações de Voluntários</h1>
<p class="text-cafe/60 text-sm mt-1">Candidaturas recebidas pelo site aguardando avaliação da coordenação.</p>

@if ($pendentes->isEmpty())
    <div class="mt-6 bg-white rounded-xl2 shadow-sm p-10 text-center text-cafe/50">
        Nenhuma candidatura pendente. 🟢
    </div>
@else
    <div class="mt-6 grid gap-4 md:grid-cols-2">
        @foreach ($pendentes as $v)
            <div class="bg-white rounded-xl2 shadow-sm p-5" x-data="{ gerar: false }">
                <div class="flex items-start justify-between">
                    <h3 class="font-bold text-marca text-lg">{{ $v->nome }}</h3>
                    <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-800">⏳ pendente</span>
                </div>
                <div class="mt-2 text-sm text-cafe/70 space-y-1">
                    @if ($v->whatsapp)<p>💬 {{ $v->whatsapp }}</p>@endif
                    @if ($v->email)<p>✉️ {{ $v->email }}</p>@endif
                    @if ($v->regiao)<p>📍 {{ $v->regiao }}</p>@endif
                    @if ($v->disponibilidade)<p>🕒 {{ $v->disponibilidade }}</p>@endif
                    @if (!empty($v->areas))
                        <div class="flex flex-wrap gap-1 pt-1">
                            @foreach ($v->areas as $a)<span class="text-xs bg-creme px-2 py-0.5 rounded-full">{{ \App\Models\Voluntario::AREAS[$a] ?? $a }}</span>@endforeach
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.aprovacoes.aprovar', $v) }}" class="mt-4 border-t border-creme pt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold mb-1">Função (aparece na carteirinha)</label>
                        <input name="funcao" value="{{ $v->funcao }}" placeholder="Ex: Voluntário de Arrecadação" class="w-full rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca">
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="gerar_acesso" value="1" x-model="gerar" class="rounded text-marca focus:ring-marca">
                        Gerar login/senha de acesso ao sistema
                    </label>

                    <div x-show="gerar" x-cloak class="grid sm:grid-cols-2 gap-3 bg-creme/60 rounded-lg p-3">
                        <div>
                            <label class="block text-xs font-semibold mb-1">E-mail de acesso</label>
                            <input type="email" name="email" value="{{ $v->email }}" class="w-full rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Nível de acesso</label>
                            <select name="role" class="w-full rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca">
                                <option value="voluntario">Voluntário(a)</option>
                                <option value="professor">Professor(a)</option>
                                <option value="diretor">Diretor(a)</option>
                                <option value="vice">Vice-Presidente</option>
                                <option value="presidente">Presidente</option>
                            </select>
                        </div>
                        <p class="sm:col-span-2 text-xs text-cafe/60">A senha será gerada automaticamente e mostrada após aprovar — anote e repasse com segurança.</p>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button class="rounded-full bg-marca text-white text-sm font-semibold px-5 py-2 hover:brightness-110">✓ Aprovar</button>
                        <button type="submit" formaction="{{ route('admin.aprovacoes.recusar', $v) }}"
                                onclick="return confirm('Recusar esta candidatura?')"
                                class="rounded-full bg-red-100 text-red-700 text-sm font-semibold px-5 py-2 hover:bg-red-200">Recusar</button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
@endif

@if ($recentes->isNotEmpty())
    <h2 class="mt-10 font-bold text-marca">Avaliadas recentemente</h2>
    <div class="mt-3 bg-white rounded-xl2 shadow-sm divide-y divide-creme">
        @foreach ($recentes as $v)
            <div class="px-4 py-3 flex items-center justify-between text-sm">
                <span>{{ $v->nome }}</span>
                <span class="text-xs px-2 py-1 rounded-full {{ $v->status === 'aprovado' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $v->status === 'aprovado' ? '✓ aprovado' : 'recusado' }}
                    @if ($v->aprovado_em) · {{ $v->aprovado_em->format('d/m') }}@endif
                </span>
            </div>
        @endforeach
    </div>
@endif
@endsection
