<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\Doacao;
use App\Models\Item;
use App\Models\Movimentacao;
use App\Models\Pessoa;
use App\Models\Presenca;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class RelatorioController extends Controller
{
    /**
     * Central de relatorios com graficos (V3).
     * Filtro de periodo: semana | mes | ano | personalizado.
     */
    public function index(Request $request): View
    {
        [$inicio, $fim, $periodo, $label] = $this->resolverPeriodo($request);

        return view('admin.relatorios.index', [
            'periodo' => $periodo,
            'label' => $label,
            'inicio' => $inicio,
            'fim' => $fim,
            'inicioStr' => $inicio->format('Y-m-d'),
            'fimStr' => $fim->format('Y-m-d'),

            // ---- Cartoes-resumo ----
            'totalDinheiro' => (float) Doacao::where('tipo', 'dinheiro')
                ->whereBetween('created_at', [$inicio, $fim])->sum('valor'),
            'totalDoacoes' => Doacao::whereBetween('created_at', [$inicio, $fim])->count(),
            'totalItens' => (float) Doacao::where('tipo', 'item')
                ->whereBetween('created_at', [$inicio, $fim])->sum('quantidade'),
            'totalEntradas' => (float) Movimentacao::where('tipo', 'entrada')
                ->whereBetween('created_at', [$inicio, $fim])->sum('quantidade'),
            'totalSaidas' => (float) Movimentacao::where('tipo', 'saida')
                ->whereBetween('created_at', [$inicio, $fim])->sum('quantidade'),

            // ---- Graficos ----
            'serieDinheiro' => $this->serieDinheiro($inicio, $fim, $periodo),
            'doacoesPorCategoria' => $this->doacoesPorCategoria($inicio, $fim),
            'entradasSaidas' => [
                'entradas' => (float) Movimentacao::where('tipo', 'entrada')->whereBetween('created_at', [$inicio, $fim])->sum('quantidade'),
                'saidas' => (float) Movimentacao::where('tipo', 'saida')->whereBetween('created_at', [$inicio, $fim])->sum('quantidade'),
            ],

            // ---- Listas ----
            'estoqueBaixo' => Item::whereColumn('quantidade', '<=', 'quantidade_minima')
                ->where('quantidade_minima', '>', 0)->orderBy('nome')->get(),
            'campanhas' => Campanha::where('ativa', true)->get(),
            'frequenciaCursos' => $this->frequenciaCursos($inicio, $fim),
            'totalAlunos' => Pessoa::where('e_aluno', true)->count(),
            'totalVoluntarios' => Pessoa::where('e_voluntario', true)->count(),
        ]);
    }

    /** Define inicio/fim conforme o filtro escolhido. */
    private function resolverPeriodo(Request $request): array
    {
        $periodo = $request->get('periodo', 'mes');

        if ($periodo === 'personalizado' && $request->filled('inicio') && $request->filled('fim')) {
            $inicio = Carbon::parse($request->get('inicio'))->startOfDay();
            $fim = Carbon::parse($request->get('fim'))->endOfDay();

            return [$inicio, $fim, 'personalizado', $inicio->format('d/m/Y').' a '.$fim->format('d/m/Y')];
        }

        return match ($periodo) {
            'semana' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek(), 'semana', 'Esta semana'],
            'ano' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear(), 'ano', 'Este ano ('.now()->year.')'],
            default => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth(), 'mes', 'Este mês ('.now()->translatedFormat('F').')'],
        };
    }

    /**
     * Serie temporal das doacoes em dinheiro (para grafico de linha).
     * Agrupa por dia (semana/mes) ou por mes (ano).
     */
    private function serieDinheiro(Carbon $inicio, Carbon $fim, string $periodo): array
    {
        $labels = [];
        $valores = [];

        if ($periodo === 'ano') {
            for ($m = 1; $m <= 12; $m++) {
                $ini = Carbon::create($inicio->year, $m, 1)->startOfMonth();
                $f = (clone $ini)->endOfMonth();
                $labels[] = $ini->translatedFormat('M');
                $valores[] = (float) Doacao::where('tipo', 'dinheiro')->whereBetween('created_at', [$ini, $f])->sum('valor');
            }
        } else {
            $cursor = $inicio->copy();
            while ($cursor <= $fim) {
                $diaIni = $cursor->copy()->startOfDay();
                $diaFim = $cursor->copy()->endOfDay();
                $labels[] = $cursor->format('d/m');
                $valores[] = (float) Doacao::where('tipo', 'dinheiro')->whereBetween('created_at', [$diaIni, $diaFim])->sum('valor');
                $cursor->addDay();
            }
        }

        return ['labels' => $labels, 'valores' => $valores];
    }

    /** Doacoes de item agrupadas por categoria (para grafico de pizza). */
    private function doacoesPorCategoria(Carbon $inicio, Carbon $fim): array
    {
        $dados = Doacao::with('categoria')
            ->whereBetween('created_at', [$inicio, $fim])
            ->get()
            ->groupBy(fn ($d) => $d->categoria?->nome ?? 'Sem categoria')
            ->map(fn ($grupo) => $grupo->count());

        return ['labels' => $dados->keys()->all(), 'valores' => $dados->values()->all()];
    }

    /** Frequencia media por curso no periodo. */
    private function frequenciaCursos(Carbon $inicio, Carbon $fim): array
    {
        $presencas = Presenca::with('curso')
            ->whereBetween('data', [$inicio, $fim])
            ->get()
            ->groupBy('curso_id');

        $resultado = [];
        foreach ($presencas as $grupo) {
            $curso = $grupo->first()->curso;
            if (! $curso) {
                continue;
            }
            $total = $grupo->count();
            $presentes = $grupo->where('presente', true)->count();
            $resultado[] = [
                'nome' => $curso->nome,
                'pct' => $total > 0 ? round($presentes / $total * 100) : 0,
                'aulas' => $grupo->pluck('data')->unique()->count(),
            ];
        }

        return $resultado;
    }
}
