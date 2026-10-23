<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Item;
use App\Models\Movimentacao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EstoqueController extends Controller
{
    public function index(): View
    {
        return view('admin.estoque.index', [
            'itens' => Item::with('categoria')->orderBy('nome')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.estoque.form', [
            'item' => new Item(['unidade' => 'un']),
            'categorias' => Categoria::orderBy('nome')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Item::create($this->validar($request));

        return redirect()->route('admin.estoque.index')->with('sucesso', 'Item cadastrado!');
    }

    public function edit(Item $estoque): View
    {
        return view('admin.estoque.form', [
            'item' => $estoque,
            'categorias' => Categoria::orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, Item $estoque): RedirectResponse
    {
        $estoque->update($this->validar($request));

        return redirect()->route('admin.estoque.index')->with('sucesso', 'Item atualizado.');
    }

    public function destroy(Item $estoque): RedirectResponse
    {
        $estoque->delete();

        return redirect()->route('admin.estoque.index')->with('sucesso', 'Item removido.');
    }

    /** Registra entrada ou saida avulsa, atualizando o saldo. */
    public function movimentar(Request $request, Item $item): RedirectResponse
    {
        $dados = $request->validate([
            'tipo' => ['required', 'in:entrada,saida'],
            'quantidade' => ['required', 'numeric', 'min:0.01'],
            'origem' => ['nullable', 'string', 'max:50'],
            'observacao' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($dados, $item, $request) {
            if ($dados['tipo'] === 'entrada') {
                $item->increment('quantidade', $dados['quantidade']);
            } else {
                $item->decrement('quantidade', min($dados['quantidade'], $item->quantidade));
            }

            Movimentacao::create([
                'item_id' => $item->id,
                'user_id' => $request->user()->id,
                'tipo' => $dados['tipo'],
                'quantidade' => $dados['quantidade'],
                'origem' => $dados['origem'] ?? 'ajuste',
                'observacao' => $dados['observacao'] ?? null,
            ]);
        });

        return back()->with('sucesso', 'Movimentação registrada.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'nome' => ['required', 'string', 'max:255'],
            'unidade' => ['required', 'string', 'max:20'],
            'quantidade' => ['nullable', 'numeric', 'min:0'],
            'quantidade_minima' => ['nullable', 'numeric', 'min:0'],
            'observacao' => ['nullable', 'string'],
        ]);
    }
}
