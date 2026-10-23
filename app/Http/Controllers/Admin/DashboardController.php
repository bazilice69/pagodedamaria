<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\Doacao;
use App\Models\Item;
use App\Models\Necessidade;
use App\Models\Voluntario;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $inicioMes = Carbon::now()->startOfMonth();

        return view('admin.dashboard', [
            'dinheiroMes' => Doacao::where('tipo', 'dinheiro')
                ->where('created_at', '>=', $inicioMes)->sum('valor'),
            'doacoesMes' => Doacao::where('created_at', '>=', $inicioMes)->count(),
            'itensArrecadados' => Doacao::where('tipo', 'item')
                ->where('created_at', '>=', $inicioMes)->sum('quantidade'),
            'voluntarios' => Voluntario::where('ativo', true)->count(),
            'campanhasAtivas' => Campanha::where('ativa', true)->count(),
            'necessidadesUrgentes' => Necessidade::where('concluida', false)
                ->whereIn('prioridade', ['urgente', 'alta'])->count(),
            'estoqueBaixo' => Item::whereColumn('quantidade', '<=', 'quantidade_minima')
                ->where('quantidade_minima', '>', 0)->get(),
            'ultimasDoacoes' => Doacao::latest()->take(8)->get(),
        ]);
    }
}
