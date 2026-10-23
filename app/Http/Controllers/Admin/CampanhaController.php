<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CampanhaController extends Controller
{
    public function index(): View
    {
        return view('admin.campanhas.index', [
            'campanhas' => Campanha::latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.campanhas.form', ['campanha' => new Campanha(['ativa' => true, 'publica' => true, 'tipo_meta' => 'dinheiro'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Campanha::create($this->validar($request));

        return redirect()->route('admin.campanhas.index')->with('sucesso', 'Campanha criada! 🎯');
    }

    public function edit(Campanha $campanha): View
    {
        return view('admin.campanhas.form', ['campanha' => $campanha]);
    }

    public function update(Request $request, Campanha $campanha): RedirectResponse
    {
        $campanha->update($this->validar($request));

        return redirect()->route('admin.campanhas.index')->with('sucesso', 'Campanha atualizada.');
    }

    public function destroy(Campanha $campanha): RedirectResponse
    {
        $campanha->delete();

        return redirect()->route('admin.campanhas.index')->with('sucesso', 'Campanha removida.');
    }

    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'tipo_meta' => ['required', 'in:dinheiro,itens'],
            'meta' => ['nullable', 'numeric', 'min:0'],
            'unidade_meta' => ['nullable', 'string', 'max:30'],
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => ['nullable', 'date'],
            'ativa' => ['nullable', 'boolean'],
            'publica' => ['nullable', 'boolean'],
            'foto' => ['nullable', 'image', 'max:4096'],
        ]);

        $dados['ativa'] = $request->boolean('ativa');
        $dados['publica'] = $request->boolean('publica');

        if ($request->hasFile('foto')) {
            $dados['foto'] = $request->file('foto')->store('campanhas', 'public');
        } else {
            unset($dados['foto']);
        }

        return $dados;
    }
}
