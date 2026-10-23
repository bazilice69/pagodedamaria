@extends('layouts.admin')
@section('titulo', 'Relatórios')

@section('conteudo')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6 no-print">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-marca">📈 Relatórios</h1>
        <p class="text-cafe/60 text-sm mt-1">Visão geral com gráficos — {{ $label }}.</p>
    </div>
    <button onclick="window.print()" class="rounded-full bg-marca text-white font-semibold px-5 py-2.5 text-sm">🖨️ Imprimir</button>
</div>

{{-- Filtro de periodo --}}
<form method="GET" class="no-print bg-white rounded-xl2 shadow-sm p-4 mb-6 flex flex-wrap items-end gap-3"
      x-data="{ p: '{{ $periodo }}' }">
    <div>
        <label class="block text-xs font-semibold mb-1">Período</label>
        <select name="periodo" x-model="p" class="rounded-lg border-cafe/20 focus:border-marca focus:ring-marca text-sm">
            <option value="semana">Esta semana</option>
            <option value="mes">Este mês</option>
            <option value="ano">Este ano</option>
            <option value="personalizado">Personalizado</option>
        </select>
    </div>
    <template x-if="p === 'personalizado'">
        <div class="flex gap-2">
            <div><label class="block text-xs font-semibold mb-1">De</label><input type="date" name="inicio" value="{{ $inicioStr }}" class="rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca"></div>
            <div><label class="block text-xs font-semibold mb-1">Até</label><input type="date" name="fim" value="{{ $fimStr }}" class="rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca"></div>
        </div>
    </template>
    <button class="btn-marca text-sm">Aplicar</button>
</form>

{{-- Cartoes-resumo --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $cards = [
            ['💰', 'Arrecadado (R$)', 'R$ '.number_format($totalDinheiro, 2, ',', '.')],
            ['❤️', 'Doações', $totalDoacoes],
            ['➕', 'Entradas no estoque', rtrim(rtrim(number_format($totalEntradas,2,',','.'),'0'),',')],
            ['➖', 'Saídas do estoque', rtrim(rtrim(number_format($totalSaidas,2,',','.'),'0'),',')],
        ];
    @endphp
    @foreach ($cards as [$i, $l, $v])
        <div class="bg-white rounded-xl2 shadow-sm p-5">
            <div class="text-2xl">{{ $i }}</div>
            <div class="mt-2 text-xl font-extrabold text-marca">{{ $v }}</div>
            <div class="text-xs text-cafe/60 mt-1">{{ $l }}</div>
        </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-2 gap-6">
    {{-- Grafico: evolucao do dinheiro --}}
    <div class="bg-white rounded-xl2 shadow-sm p-5">
        <h2 class="font-bold text-marca mb-3">💰 Doações em dinheiro — {{ $label }}</h2>
        <canvas id="graficoDinheiro" height="160"></canvas>
    </div>

    {{-- Grafico: doacoes por categoria --}}
    <div class="bg-white rounded-xl2 shadow-sm p-5">
        <h2 class="font-bold text-marca mb-3">❤️ Doações por categoria</h2>
        @if (empty($doacoesPorCategoria['labels']))
            <p class="text-sm text-cafe/50 py-10 text-center">Sem doações no período.</p>
        @else
            <canvas id="graficoCategorias" height="160"></canvas>
        @endif
    </div>

    {{-- Grafico: entradas x saidas --}}
    <div class="bg-white rounded-xl2 shadow-sm p-5">
        <h2 class="font-bold text-marca mb-3">📦 Entradas x Saídas (estoque)</h2>
        <canvas id="graficoEstoque" height="160"></canvas>
    </div>

    {{-- Frequencia dos cursos --}}
    <div class="bg-white rounded-xl2 shadow-sm p-5">
        <h2 class="font-bold text-marca mb-3">🎓 Frequência média por curso</h2>
        @if (empty($frequenciaCursos))
            <p class="text-sm text-cafe/50 py-10 text-center">Sem chamadas registradas no período.</p>
        @else
            <ul class="space-y-3">
                @foreach ($frequenciaCursos as $f)
                    <li>
                        <div class="flex justify-between text-sm mb-1"><span>{{ $f['nome'] }}</span><span class="font-semibold {{ $f['pct'] >= 75 ? 'text-green-600' : ($f['pct'] >= 50 ? 'text-orange-500' : 'text-red-500') }}">{{ $f['pct'] }}%</span></div>
                        <div class="barra-progresso"><span style="width: {{ $f['pct'] }}%"></span></div>
                        <div class="text-xs text-cafe/50 mt-0.5">{{ $f['aulas'] }} aula(s)</div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

{{-- Estoque baixo --}}
@if ($estoqueBaixo->isNotEmpty())
<div class="mt-6 bg-white rounded-xl2 shadow-sm p-5">
    <h2 class="font-bold text-marca mb-3">⚠️ Itens com estoque baixo</h2>
    <div class="flex flex-wrap gap-2">
        @foreach ($estoqueBaixo as $item)
            <span class="text-sm bg-red-50 text-red-700 rounded-full px-3 py-1">🔴 {{ $item->nome }} ({{ rtrim(rtrim(number_format($item->quantidade,2,',','.'),'0'),',') }} {{ $item->unidade }})</span>
        @endforeach
    </div>
</div>
@endif

{{-- Totais gerais --}}
<div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="bg-white rounded-xl2 shadow-sm p-4 text-center"><div class="text-2xl font-extrabold text-marca">{{ $totalAlunos }}</div><div class="text-xs text-cafe/60">alunos</div></div>
    <div class="bg-white rounded-xl2 shadow-sm p-4 text-center"><div class="text-2xl font-extrabold text-marca">{{ $totalVoluntarios }}</div><div class="text-xs text-cafe/60">voluntários</div></div>
    <div class="bg-white rounded-xl2 shadow-sm p-4 text-center"><div class="text-2xl font-extrabold text-marca">{{ $campanhas->count() }}</div><div class="text-xs text-cafe/60">campanhas ativas</div></div>
    <div class="bg-white rounded-xl2 shadow-sm p-4 text-center"><div class="text-2xl font-extrabold text-marca">{{ $estoqueBaixo->count() }}</div><div class="text-xs text-cafe/60">alertas de estoque</div></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const verde = '#164A36', terracota = '#C96F4A', manteiga = '#F2C94C';
        if (!window.Chart) return;

        // Linha — dinheiro
        new Chart(document.getElementById('graficoDinheiro'), {
            type: 'line',
            data: {
                labels: @json($serieDinheiro['labels']),
                datasets: [{ label: 'R$ arrecadado', data: @json($serieDinheiro['valores']),
                    borderColor: verde, backgroundColor: 'rgba(22,74,54,.1)', fill: true, tension: .3 }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });

        @if (!empty($doacoesPorCategoria['labels']))
        // Pizza — categorias
        new Chart(document.getElementById('graficoCategorias'), {
            type: 'doughnut',
            data: {
                labels: @json($doacoesPorCategoria['labels']),
                datasets: [{ data: @json($doacoesPorCategoria['valores']),
                    backgroundColor: [verde, terracota, manteiga, '#1E6B4D', '#E08E6B', '#A8562F', '#999'] }]
            },
            options: { responsive: true }
        });
        @endif

        // Barra — entradas x saidas
        new Chart(document.getElementById('graficoEstoque'), {
            type: 'bar',
            data: {
                labels: ['Entradas', 'Saídas'],
                datasets: [{ data: [{{ $entradasSaidas['entradas'] }}, {{ $entradasSaidas['saidas'] }}],
                    backgroundColor: [verde, terracota] }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    });
</script>
@endsection
