<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Necessidade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NecessidadeController extends Controller
{
    public function index(): View
    {
        return view('admin.necessidades.index', [
            'necessidades' => Necessidade::with('categoria')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.necessidades.form', [
            'necessidade' => new Necessidade(['prioridade' => 'media', 'publica' => true, 'unidade' => 'un']),
            'categorias' => Categoria::orderBy('nome')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Necessidade::create($this->validar($request));

        return redirect()->route('admin.necessidades.index')->with('sucesso', 'Necessidade cadastrada!');
    }

    public function edit(Necessidade $necessidade): View
    {
        return view('admin.necessidades.form', [
            'necessidade' => $necessidade,
            'categorias' => Categoria::orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, Necessidade $necessidade): RedirectResponse
    {
        $necessidade->update($this->validar($request));

        return redirect()->route('admin.necessidades.index')->with('sucesso', 'Necessidade atualizada.');
    }

    public function destroy(Necessidade $necessidade): RedirectResponse
    {
        $necessidade->delete();

        return redirect()->route('admin.necessidades.index')->with('sucesso', 'Necessidade removida.');
    }

    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'meta' => ['required', 'numeric', 'min:0'],
            'unidade' => ['required', 'string', 'max:20'],
            'arrecadado' => ['nullable', 'numeric', 'min:0'],
            'prioridade' => ['required', 'in:baixa,media,alta,urgente'],
            'prazo' => ['nullable', 'date'],
            'concluida' => ['nullable', 'boolean'],
            'publica' => ['nullable', 'boolean'],
        ]);

        $dados['concluida'] = $request->boolean('concluida');
        $dados['publica'] = $request->boolean('publica');

        return $dados;
    }
}
