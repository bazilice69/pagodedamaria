<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use App\Models\Presenca;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PortalController extends Controller
{
    /**
     * Portal do Pai/Responsavel — acesso LEVE, sem senha.
     * Entra com o codigo (ID) do aluno + confirmacao leve (data de nascimento).
     */
    public function form(): View
    {
        return view('public.portal.entrar');
    }

    public function entrar(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'codigo' => ['required', 'string', 'max:30'],
            'nascimento' => ['required', 'date'],
        ]);

        $pessoa = Pessoa::where('codigo', trim($dados['codigo']))
            ->orWhere('codigo', 'PDM-'.str_pad(preg_replace('/\D/', '', $dados['codigo']), 6, '0', STR_PAD_LEFT))
            ->first();

        // Confere a data de nascimento como "senha leve".
        if (! $pessoa || ! $pessoa->nascimento
            || $pessoa->nascimento->format('Y-m-d') !== $dados['nascimento']) {
            return back()->withInput()->with('erro', 'Código ou data de nascimento não conferem. Peça ajuda à coordenação.');
        }

        // Guarda o acesso na sessao (token leve).
        Session::put('portal_pessoa_id', $pessoa->id);

        return redirect()->route('portal.painel');
    }

    public function painel(): View
    {
        $pessoa = $this->pessoaDaSessao();

        $pessoa->load(['cursos', 'responsavel']);

        // Frequencia resumida por curso.
        $frequencia = [];
        foreach ($pessoa->cursos as $curso) {
            $total = Presenca::where('curso_id', $curso->id)->where('pessoa_id', $pessoa->id)->count();
            $presentes = Presenca::where('curso_id', $curso->id)->where('pessoa_id', $pessoa->id)->where('presente', true)->count();
            $frequencia[] = [
                'curso' => $curso,
                'total' => $total,
                'presentes' => $presentes,
                'pct' => $total > 0 ? round($presentes / $total * 100) : null,
            ];
        }

        return view('public.portal.painel', ['pessoa' => $pessoa, 'frequencia' => $frequencia]);
    }

    public function sair(): RedirectResponse
    {
        Session::forget('portal_pessoa_id');

        return redirect()->route('portal.entrar');
    }

    private function pessoaDaSessao(): Pessoa
    {
        $id = Session::get('portal_pessoa_id');
        abort_unless($id, 403);

        return Pessoa::findOrFail($id);
    }
}
