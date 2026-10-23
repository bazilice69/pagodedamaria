<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EquipeController extends Controller
{
    /** Niveis que a equipe pode ter (presidencia so via transferir). */
    private const NIVEIS = [
        User::ROLE_VICE => 'Vice-Presidente',
        User::ROLE_DIRETOR => 'Diretor(a)',
        User::ROLE_PROFESSOR => 'Professor(a)',
        User::ROLE_VOLUNTARIO => 'Voluntário(a)',
    ];

    public function index(): View
    {
        return view('admin.equipe.index', [
            'membros' => User::orderByRaw("CASE role
                WHEN 'presidente' THEN 1 WHEN 'admin' THEN 1 WHEN 'vice' THEN 2
                WHEN 'diretor' THEN 3 WHEN 'professor' THEN 4 ELSE 5 END")
                ->orderBy('name')->get(),
            'niveis' => self::NIVEIS,
            'presidente' => User::whereIn('role', [User::ROLE_PRESIDENTE, User::ROLE_ADMIN])->first(),
        ]);
    }

    public function create(): View
    {
        return view('admin.equipe.form', ['membro' => new User(['role' => User::ROLE_VOLUNTARIO]), 'niveis' => self::NIVEIS]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(array_keys(self::NIVEIS))],
            'cargo' => ['nullable', 'string', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:30'],
        ]);

        User::create([
            ...$dados,
            'password' => Hash::make($dados['password']),
            'ativo' => true,
            'status' => User::STATUS_APROVADO,
            'aprovado_por' => $request->user()->id,
            'aprovado_em' => now(),
        ]);

        return redirect()->route('admin.equipe.index')->with('sucesso', 'Membro da equipe cadastrado! 💜');
    }

    public function edit(User $equipe): View
    {
        return view('admin.equipe.form', ['membro' => $equipe, 'niveis' => self::NIVEIS]);
    }

    public function update(Request $request, User $equipe): RedirectResponse
    {
        // Nao permite rebaixar o presidente por aqui (so via transferir presidencia).
        if ($equipe->isPresidente()) {
            return back()->with('sucesso', 'Para mudar o presidente, use "Transferir presidência".');
        }

        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($equipe->id)],
            'role' => ['required', Rule::in(array_keys(self::NIVEIS))],
            'cargo' => ['nullable', 'string', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'ativo' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $equipe->fill([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'role' => $dados['role'],
            'cargo' => $dados['cargo'] ?? null,
            'telefone' => $dados['telefone'] ?? null,
            'ativo' => $request->boolean('ativo', true),
        ]);

        if (filled($dados['password'] ?? null)) {
            $equipe->password = Hash::make($dados['password']);
        }

        $equipe->save();

        return redirect()->route('admin.equipe.index')->with('sucesso', 'Membro atualizado.');
    }

    public function destroy(Request $request, User $equipe): RedirectResponse
    {
        if ($equipe->isPresidente()) {
            return back()->with('sucesso', 'O presidente não pode ser removido. Transfira a presidência antes.');
        }
        if ($equipe->id === $request->user()->id) {
            return back()->with('sucesso', 'Você não pode remover a própria conta.');
        }

        $equipe->delete();

        return redirect()->route('admin.equipe.index')->with('sucesso', 'Membro removido.');
    }

    /**
     * Transfere a presidencia (Opção A): o novo vira presidente e o antigo
     * vira vice automaticamente. So existe 1 presidente por vez.
     */
    public function transferir(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'novo_presidente_id' => ['required', 'exists:users,id'],
            'confirmar' => ['accepted'],
        ]);

        $atual = $request->user();
        $novo = User::findOrFail($dados['novo_presidente_id']);

        if ($novo->id === $atual->id) {
            return back()->with('sucesso', 'Essa pessoa já é a presidente.');
        }

        DB::transaction(function () use ($atual, $novo) {
            // Rebaixa TODOS os presidentes atuais para vice (garante 1 so).
            User::whereIn('role', [User::ROLE_PRESIDENTE, User::ROLE_ADMIN])
                ->update(['role' => User::ROLE_VICE, 'cargo' => 'Vice-Presidente']);

            // Promove o novo.
            $novo->update([
                'role' => User::ROLE_PRESIDENTE,
                'cargo' => 'Presidente',
                'ativo' => true,
                'status' => User::STATUS_APROVADO,
            ]);
        });

        return redirect()->route('admin.equipe.index')
            ->with('sucesso', "Presidência transferida para {$novo->name}. Você agora é Vice-Presidente.");
    }
}
