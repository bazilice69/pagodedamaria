<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voluntario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VoluntarioController extends Controller
{
    public function index(): View
    {
        return view('admin.voluntarios.index', [
            'voluntarios' => Voluntario::latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.voluntarios.form', [
            'voluntario' => new Voluntario(['ativo' => true]),
            'areas' => Voluntario::AREAS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Voluntario::create($this->validar($request));

        return redirect()->route('admin.voluntarios.index')->with('sucesso', 'Voluntário cadastrado!');
    }

    public function edit(Voluntario $voluntario): View
    {
        return view('admin.voluntarios.form', [
            'voluntario' => $voluntario,
            'areas' => Voluntario::AREAS,
        ]);
    }

    public function update(Request $request, Voluntario $voluntario): RedirectResponse
    {
        $voluntario->update($this->validar($request));

        return redirect()->route('admin.voluntarios.index')->with('sucesso', 'Voluntário atualizado.');
    }

    public function destroy(Voluntario $voluntario): RedirectResponse
    {
        $voluntario->delete();

        return redirect()->route('admin.voluntarios.index')->with('sucesso', 'Voluntário removido.');
    }

    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'areas' => ['nullable', 'array'],
            'disponibilidade' => ['nullable', 'string', 'max:255'],
            'regiao' => ['nullable', 'string', 'max:255'],
            'observacao' => ['nullable', 'string'],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $dados['ativo'] = $request->boolean('ativo');

        return $dados;
    }
}
