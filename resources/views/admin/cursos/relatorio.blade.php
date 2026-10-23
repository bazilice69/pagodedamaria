@extends('layouts.admin')
@section('titulo', 'Relatório — '.$curso->nome)

@section('conteudo')
<a href="{{ route('admin.cursos.show', $curso) }}" class="text-terracota text-sm font-semibold hover:underline">← voltar ao curso</a>
<div class="flex items-center justify-between gap-4 mt-2 mb-6 no-print">
    <h1 class="text-2xl font-extrabold text-marca">📊 Frequência: {{ $curso->nome }}</h1>
    <button onclick="window.print()" class="rounded-full bg-marca text-white font-semibold px-5 py-2.5 text-sm">🖨️ Imprimir</button>
</div>

@if ($datas->isEmpty())
    <div class="bg-white rounded-xl2 shadow-sm p-10 text-center text-cafe/50">
        Ainda não há chamadas registradas. <a href="{{ route('admin.cursos.presenca', $curso) }}" class="text-terracota hover:underline">fazer a primeira chamada</a>
    </div>
@else
    {{-- Resumo por aula --}}
    <div class="bg-white rounded-xl2 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-marca mb-3">Por aula (datas com chamada)</h2>
        <div class="flex flex-wrap gap-2">
            @foreach ($resumoAulas as $aula)
                <div class="bg-creme rounded-lg px-3 py-2 text-sm text-center">
                    <div class="font-semibold">{{ $aula->data->format('d/m') }}</div>
                    <div class="text-xs text-cafe/60">{{ (int)$aula->presentes }}/{{ $aula->total }} presentes</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Por aluno (% de presenca) --}}
    <div class="bg-white rounded-xl2 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-creme text-cafe/70 text-left">
                <tr>
                    <th class="px-4 py-3 sticky left-0 bg-creme">Aluno</th>
                    @foreach ($datas as $d)
                        <th class="px-2 py-3 text-center whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($d)->format('d/m') }}</th>
                    @endforeach
                    <th class="px-4 py-3 text-center">Frequência</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-creme">
                @foreach ($curso->alunos as $aluno)
                    @php
                        $presencasAluno = $registros[$aluno->id] ?? collect();
                        $totalPresente = 0;
                        foreach ($datas as $d) {
                            if (optional($presencasAluno->get($d))->presente) $totalPresente++;
                        }
                        $pct = $datas->count() > 0 ? round($totalPresente / $datas->count() * 100) : 0;
                    @endphp
                    <tr class="hover:bg-creme/40">
                        <td class="px-4 py-3 font-semibold text-marca sticky left-0 bg-white">{{ $aluno->nome }}</td>
                        @foreach ($datas as $d)
                            @php $reg = $presencasAluno->get($d); @endphp
                            <td class="px-2 py-3 text-center">
                                @if ($reg === null)
                                    <span class="text-cafe/30">–</span>
                                @elseif ($reg->presente)
                                    <span class="text-green-600">✓</span>
                                @else
                                    <span class="text-red-500">✗</span>
                                @endif
                            </td>
                        @endforeach
                        <td class="px-4 py-3 text-center font-bold {{ $pct >= 75 ? 'text-green-600' : ($pct >= 50 ? 'text-orange-500' : 'text-red-500') }}">
                            {{ $pct }}%
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="text-xs text-cafe/50 mt-3">✓ presente · ✗ faltou · – sem registro naquela data</p>
@endif
@endsection
