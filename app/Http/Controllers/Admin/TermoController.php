<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracao;
use App\Models\ModeloTermo;
use App\Models\Pessoa;
use App\Models\TermoGerado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TermoController extends Controller
{
    // ===== Modelos de termo =====
    public function index(): View
    {
        return view('admin.termos.index', [
            'modelos' => ModeloTermo::orderBy('titulo')->get(),
            'gerados' => TermoGerado::with('pessoa')->latest()->take(20)->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.termos.form', ['modelo' => new ModeloTermo(['tipo' => 'passeio', 'ativo' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        ModeloTermo::create($this->validar($request));

        return redirect()->route('admin.termos.index')->with('sucesso', 'Modelo de termo criado! 📋');
    }

    public function edit(ModeloTermo $termo): View
    {
        return view('admin.termos.form', ['modelo' => $termo]);
    }

    public function update(Request $request, ModeloTermo $termo): RedirectResponse
    {
        $termo->update($this->validar($request));

        return redirect()->route('admin.termos.index')->with('sucesso', 'Modelo atualizado.');
    }

    public function destroy(ModeloTermo $termo): RedirectResponse
    {
        $termo->delete();

        return redirect()->route('admin.termos.index')->with('sucesso', 'Modelo removido.');
    }

    // ===== Geracao de termo para uma pessoa =====
    /** Escolhe a pessoa e gera o termo preenchido. */
    public function gerar(Request $request, ModeloTermo $termo): RedirectResponse
    {
        $dados = $request->validate(['pessoa_id' => ['required', 'exists:pessoas,id']]);
        $pessoa = Pessoa::findOrFail($dados['pessoa_id']);

        $conteudo = $this->preencher($termo->conteudo, $pessoa);

        $gerado = TermoGerado::create([
            'modelo_termo_id' => $termo->id,
            'pessoa_id' => $pessoa->id,
            'gerado_por' => $request->user()->id,
            'titulo' => $termo->titulo,
            'conteudo' => $conteudo, // congelado neste momento
            'status' => 'gerado',
        ]);

        return redirect()->route('admin.termos.imprimir', $gerado);
    }

    /** Pagina de impressao do termo preenchido. */
    public function imprimir(TermoGerado $gerado): View
    {
        return view('admin.termos.imprimir', ['gerado' => $gerado]);
    }

    /** Substitui os placeholders pelos dados reais da pessoa. */
    private function preencher(string $texto, Pessoa $pessoa): string
    {
        $assoc = Configuracao::instancia();

        $mapa = [
            '{{nome}}' => $pessoa->nome,
            '{{responsavel}}' => $pessoa->responsavel?->nome ?? '____________________',
            '{{codigo}}' => $pessoa->codigo ?? '',
            '{{nascimento}}' => optional($pessoa->nascimento)->format('d/m/Y') ?? '__/__/____',
            '{{cpf}}' => $pessoa->cpf ?? '____________',
            '{{endereco}}' => $pessoa->endereco_completo ?: '____________________',
            '{{associacao}}' => $assoc->nome ?? 'Pagode da Dona Maria',
            '{{data}}' => now()->format('d/m/Y'),
        ];

        return strtr($texto, $mapa);
    }

    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'string', 'max:30'],
            'conteudo' => ['required', 'string'],
            'ativo' => ['nullable', 'boolean'],
        ]);
        $dados['ativo'] = $request->boolean('ativo', true);

        return $dados;
    }
}
