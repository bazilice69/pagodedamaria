<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use Illuminate\View\View;

class VerificacaoController extends Controller
{
    /**
     * Pagina publica aberta ao escanear o QR da carteirinha.
     * Mostra apenas o MINIMO para validar (nome, codigo, cargo/papel, foto se
     * autorizada) — respeita a privacidade (nada de CPF, endereco, etc.).
     */
    public function carteirinha(string $codigo): View
    {
        $pessoa = Pessoa::where('codigo', $codigo)
            ->orWhere('id', $codigo)
            ->firstOrFail();

        return view('public.verificacao', ['pessoa' => $pessoa]);
    }
}
