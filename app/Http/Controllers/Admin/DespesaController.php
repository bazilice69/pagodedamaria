<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Despesa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DespesaController extends Controller
{
    public function index(Request $request): View
    {
        $mes = $request->get('mes', Carbon::now()->format('Y-m'));
        [$ano, $m] = explode('-', $mes);
        $inicio = Carbon::create((int) $ano, (int) $m, 1)->startOfMonth();
        $fim = (clone $inicio)->endOfMonth();

        $despesas = Despesa::whereBetween('data', [$inicio, $fim])->orderBy('data', 'desc')->get();

        return view('admin.despesas.index', [
            'despesas' => $despesas,
            'mes' => $mes,
            'total' => $despesas->sum('valor'),
            'labelMes' => $inicio->translatedFormat('F \d\e Y'),
        ]);
    }

    public function create(): View
    {
        return view('admin.despesas.form', ['despesa' => new Despesa(['data' => now()->format('Y-m-d'), 'paga' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $this->validar($request);
        $dados['registrado_por'] = $request->user()->id;
        Despesa::create($dados);

        return redirect()->route('admin.despesas.index')->with('sucesso', 'Despesa registrada.');
    }

    public function edit(Despesa $despesa): View
    {
        return view('admin.despesas.form', ['despesa' => $despesa]);
    }

    public function update(Request $request, Despesa $despesa): RedirectResponse
    {
        $despesa->update($this->validar($request));

        return redirect()->route('admin.despesas.index')->with('sucesso', 'Despesa atualizada.');
    }

    public function destroy(Despesa $despesa): RedirectResponse
    {
        $despesa->delete();

        return redirect()->route('admin.despesas.index')->with('sucesso', 'Despesa removida.');
    }

    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'valor' => ['required', 'numeric', 'min:0'],
            'data' => ['required', 'date'],
            'paga' => ['nullable', 'boolean'],
            'recorrente' => ['nullable', 'boolean'],
            'observacao' => ['nullable', 'string'],
        ]);
        $dados['paga'] = $request->boolean('paga');
        $dados['recorrente'] = $request->boolean('recorrente');

        return $dados;
    }
}
