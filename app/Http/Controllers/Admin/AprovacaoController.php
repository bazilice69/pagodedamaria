<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Voluntario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AprovacaoController extends Controller
{
    /** Lista as candidaturas de voluntarios aguardando avaliacao. */
    public function index(): View
    {
        return view('admin.aprovacoes.index', [
            'pendentes' => Voluntario::pendentes()->latest()->get(),
            'recentes' => Voluntario::whereIn('status', [Voluntario::STATUS_APROVADO, Voluntario::STATUS_RECUSADO])
                ->latest('aprovado_em')->take(10)->get(),
        ]);
    }

    /**
     * Aprova a candidatura. Opcionalmente gera uma conta de acesso
     * (login/senha) para o voluntario, com papel e cargo definidos pelo gestor.
     */
    public function aprovar(Request $request, Voluntario $voluntario): RedirectResponse
    {
        $dados = $request->validate([
            'funcao' => ['nullable', 'string', 'max:255'],
            'gerar_acesso' => ['nullable', 'boolean'],
            'email' => ['nullable', 'email', 'max:255', 'required_if:gerar_acesso,1'],
            'role' => ['nullable', 'in:voluntario,professor,diretor,vice,presidente'],
        ]);

        $voluntario->update([
            'status' => Voluntario::STATUS_APROVADO,
            'ativo' => true,
            'funcao' => $dados['funcao'] ?? $voluntario->funcao,
            'aprovado_por' => $request->user()->id,
            'aprovado_em' => now(),
        ]);

        $msg = 'Voluntário aprovado! 💜';
        $senhaGerada = null;

        // Gera conta de acesso, se o gestor pediu.
        if ($request->boolean('gerar_acesso') && filled($dados['email'] ?? null)) {
            $senhaGerada = Str::password(8, symbols: false);
            $user = User::create([
                'name' => $voluntario->nome,
                'email' => $dados['email'],
                'password' => Hash::make($senhaGerada),
                'role' => $dados['role'] ?? User::ROLE_VOLUNTARIO,
                'cargo' => $dados['funcao'] ?? null,
                'telefone' => $voluntario->whatsapp,
                'ativo' => true,
                'status' => User::STATUS_APROVADO,
                'aprovado_por' => $request->user()->id,
                'aprovado_em' => now(),
            ]);
            $voluntario->update(['user_id' => $user->id]);
            $msg = "Voluntário aprovado e acesso criado! Login: {$dados['email']} · Senha: {$senhaGerada} (anote e repasse com segurança).";
        }

        return redirect()->route('admin.aprovacoes.index')->with('sucesso', $msg);
    }

    public function recusar(Request $request, Voluntario $voluntario): RedirectResponse
    {
        $voluntario->update([
            'status' => Voluntario::STATUS_RECUSADO,
            'ativo' => false,
            'aprovado_por' => $request->user()->id,
            'aprovado_em' => now(),
        ]);

        return redirect()->route('admin.aprovacoes.index')->with('sucesso', 'Candidatura recusada.');
    }
}
