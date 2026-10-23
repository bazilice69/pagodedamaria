<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\Necessidade;
use Illuminate\View\View;

class PaginaController extends Controller
{
    public function inicio(): View
    {
        return view('public.inicio', [
            'campanhas' => Campanha::where('publica', true)->where('ativa', true)
                ->latest()->take(3)->get(),
            'necessidades' => Necessidade::where('publica', true)->where('concluida', false)
                ->orderByRaw("CASE prioridade WHEN 'urgente' THEN 1 WHEN 'alta' THEN 2 WHEN 'media' THEN 3 ELSE 4 END")
                ->take(4)->get(),
            'parceiros' => \App\Models\Parceiro::ativos()->get(),
        ]);
    }

    public function sobre(): View
    {
        return view('public.sobre');
    }

    public function campanhas(): View
    {
        return view('public.campanhas', [
            'campanhas' => Campanha::where('publica', true)->where('ativa', true)
                ->latest()->paginate(9),
        ]);
    }

    public function campanha(Campanha $campanha): View
    {
        abort_unless($campanha->publica, 404);

        return view('public.campanha', ['campanha' => $campanha]);
    }

    public function necessidades(): View
    {
        return view('public.necessidades', [
            'necessidades' => Necessidade::where('publica', true)->where('concluida', false)
                ->orderByRaw("CASE prioridade WHEN 'urgente' THEN 1 WHEN 'alta' THEN 2 WHEN 'media' THEN 3 ELSE 4 END")
                ->paginate(12),
        ]);
    }

    public function comoAjudar(): View
    {
        return view('public.como-ajudar');
    }

    public function contato(): View
    {
        return view('public.contato');
    }
}
