<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Voluntario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueroAjudarController extends Controller
{
    public function formVoluntario(): View
    {
        return view('public.voluntario', ['areas' => Voluntario::AREAS]);
    }

    public function storeVoluntario(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'areas' => ['nullable', 'array'],
            'areas.*' => ['string', 'in:'.implode(',', array_keys(Voluntario::AREAS))],
            'disponibilidade' => ['nullable', 'string', 'max:255'],
            'regiao' => ['nullable', 'string', 'max:255'],
        ]);

        // Candidatura entra PENDENTE e sem acesso. So um gestor (presidente/
        // vice/diretor) aprova e, se quiser, gera login/senha depois.
        $dados['status'] = Voluntario::STATUS_PENDENTE;
        $dados['ativo'] = false;

        Voluntario::create($dados);

        return redirect()->route('voluntario.form')
            ->with('sucesso', 'Recebemos sua candidatura! A coordenação vai avaliar e entrar em contato. Obrigado por querer ajudar! 💜');
    }
}
