<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\Categoria;
use App\Models\Doacao;
use App\Models\Item;
use App\Models\Movimentacao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DoacaoController extends Controller
{
    public function index(): View
    {
        return view('admin.doacoes.index', [
            'doacoes' => Doacao::with(['campanha', 'categoria', 'item'])->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.doacoes.form', $this->opcoes() + ['doacao' => new Doacao]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $this->validar($request);

        DB::transaction(function () use ($dados, $request) {
            $dados['recebido_por'] = $request->user()->id;
            $doacao = Doacao::create($dados);

            // Se for item vinculado ao estoque, da entrada automatica.
            if ($doacao->tipo === 'item' && $doacao->item_id && $doacao->quantidade > 0) {
                $item = Item::find($doacao->item_id);
                $item->increment('quantidade', $doacao->quantidade);
                $doacao->update(['entrou_estoque' => true]);
                Movimentacao::create([
                    'item_id' => $item->id,
                    'user_id' => $request->user()->id,
                    'tipo' => 'entrada',
                    'quantidade' => $doacao->quantidade,
                    'origem' => 'doacao',
                    'referencia_type' => Doacao::class,
                    'referencia_id' => $doacao->id,
                ]);
            }

            // Soma em campanha, se vinculada.
            if ($doacao->campanha_id) {
                $valor = $doacao->tipo === 'dinheiro' ? $doacao->valor : $doacao->quantidade;
                Campanha::where('id', $doacao->campanha_id)->increment('arrecadado', (float) $valor);
            }
        });

        return redirect()->route('admin.doacoes.index')->with('sucesso', 'Doação registrada! 💜');
    }

    public function edit(Doacao $doacoe): View
    {
        return view('admin.doacoes.form', $this->opcoes() + ['doacao' => $doacoe]);
    }

    public function update(Request $request, Doacao $doacoe): RedirectResponse
    {
        $doacoe->update($this->validar($request));

        return redirect()->route('admin.doacoes.index')->with('sucesso', 'Doação atualizada.');
    }

    public function destroy(Doacao $doacoe): RedirectResponse
    {
        $doacoe->delete();

        return redirect()->route('admin.doacoes.index')->with('sucesso', 'Doação removida.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'tipo' => ['required', 'in:dinheiro,item'],
            'campanha_id' => ['nullable', 'exists:campanhas,id'],
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'item_id' => ['nullable', 'exists:itens,id'],
            'doador_nome' => ['nullable', 'string', 'max:255'],
            'doador_contato' => ['nullable', 'string', 'max:255'],
            'anonimo' => ['nullable', 'boolean'],
            'descricao' => ['nullable', 'string', 'max:255'],
            'quantidade' => ['nullable', 'numeric', 'min:0'],
            'unidade' => ['nullable', 'string', 'max:20'],
            'valor' => ['nullable', 'numeric', 'min:0'],
            'observacao' => ['nullable', 'string'],
            'data' => ['nullable', 'date'],
        ]);
    }

    private function opcoes(): array
    {
        return [
            'campanhas' => Campanha::orderBy('titulo')->get(),
            'categorias' => Categoria::orderBy('nome')->get(),
            'itens' => Item::orderBy('nome')->get(),
        ];
    }
}
