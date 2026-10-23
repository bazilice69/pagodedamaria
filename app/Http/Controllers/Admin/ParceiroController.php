<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parceiro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParceiroController extends Controller
{
    public function index(): View
    {
        return view('admin.parceiros.index', ['parceiros' => Parceiro::ativos()->get()]);
    }

    public function create(): View
    {
        return view('admin.parceiros.form', ['parceiro' => new Parceiro(['ativo' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Parceiro::create($this->validar($request));

        return redirect()->route('admin.parceiros.index')->with('sucesso', 'Parceiro cadastrado! 🤝');
    }

    public function edit(Parceiro $parceiro): View
    {
        return view('admin.parceiros.form', ['parceiro' => $parceiro]);
    }

    public function update(Request $request, Parceiro $parceiro): RedirectResponse
    {
        $parceiro->update($this->validar($request));

        return redirect()->route('admin.parceiros.index')->with('sucesso', 'Parceiro atualizado.');
    }

    public function destroy(Parceiro $parceiro): RedirectResponse
    {
        $parceiro->delete();

        return redirect()->route('admin.parceiros.index')->with('sucesso', 'Parceiro removido.');
    }

    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'site' => ['nullable', 'string', 'max:255'],
            'frase' => ['nullable', 'string', 'max:255'],
            'ordem' => ['nullable', 'integer', 'min:0'],
            'ativo' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);
        $dados['ativo'] = $request->boolean('ativo', true);

        if ($request->hasFile('logo')) {
            $dados['logo'] = $request->file('logo')->store('parceiros', 'public');
        } else {
            unset($dados['logo']);
        }

        return $dados;
    }
}
