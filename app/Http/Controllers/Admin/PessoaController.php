<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use App\Models\TipoBeneficio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PessoaController extends Controller
{
    public function index(Request $request): View
    {
        $pessoas = Pessoa::busca($request->get('q'))
            ->when($request->get('papel'), function ($query, $papel) {
                $campo = 'e_'.$papel;
                if (in_array($campo, ['e_aluno', 'e_responsavel', 'e_voluntario', 'e_doador', 'e_beneficiario'])) {
                    $query->where($campo, true);
                }
            })
            ->orderBy('nome')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pessoas.index', [
            'pessoas' => $pessoas,
            'q' => $request->get('q'),
            'papel' => $request->get('papel'),
        ]);
    }

    public function create(): View
    {
        return view('admin.pessoas.form', [
            'pessoa' => new Pessoa(['ativo' => true]),
            'responsaveis' => Pessoa::where('e_responsavel', true)->orderBy('nome')->get(),
            'tiposBeneficio' => TipoBeneficio::where('ativo', true)->orderBy('nome')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $pessoa = Pessoa::create($this->validar($request));
        $this->salvarFoto($request, $pessoa);
        $this->salvarBeneficios($request, $pessoa);

        return redirect()->route('admin.pessoas.show', $pessoa)->with('sucesso', 'Cadastro criado! 💜');
    }

    public function show(Pessoa $pessoa): View
    {
        $pessoa->load(['responsavel', 'dependentes', 'beneficios.tipo', 'cursos']);

        return view('admin.pessoas.show', ['pessoa' => $pessoa]);
    }

    public function edit(Pessoa $pessoa): View
    {
        return view('admin.pessoas.form', [
            'pessoa' => $pessoa->load('beneficios'),
            'responsaveis' => Pessoa::where('e_responsavel', true)->where('id', '!=', $pessoa->id)->orderBy('nome')->get(),
            'tiposBeneficio' => TipoBeneficio::where('ativo', true)->orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, Pessoa $pessoa): RedirectResponse
    {
        $pessoa->update($this->validar($request));
        $this->salvarFoto($request, $pessoa);
        $this->salvarBeneficios($request, $pessoa);

        return redirect()->route('admin.pessoas.show', $pessoa)->with('sucesso', 'Cadastro atualizado.');
    }

    public function destroy(Pessoa $pessoa): RedirectResponse
    {
        $pessoa->delete();

        return redirect()->route('admin.pessoas.index')->with('sucesso', 'Cadastro removido.');
    }

    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'apelido' => ['nullable', 'string', 'max:255'],
            'nascimento' => ['nullable', 'date'],
            'sexo' => ['nullable', 'string', 'max:20'],
            'cpf' => ['nullable', 'string', 'max:20'],
            'rg' => ['nullable', 'string', 'max:20'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'cep' => ['nullable', 'string', 'max:9'],
            'logradouro' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'max:2'],
            'responsavel_id' => ['nullable', 'exists:pessoas,id'],
            'parentesco' => ['nullable', 'string', 'max:50'],
            'observacao' => ['nullable', 'string'],
            'autoriza_imagem' => ['nullable', 'boolean'],
        ]);

        // Papeis (checkboxes)
        foreach (['e_aluno', 'e_responsavel', 'e_voluntario', 'e_doador', 'e_beneficiario'] as $papel) {
            $dados[$papel] = $request->boolean($papel);
        }

        $dados['autoriza_imagem'] = $request->boolean('autoriza_imagem');
        $dados['autoriza_imagem_data'] = $dados['autoriza_imagem'] ? now() : null;
        $dados['ativo'] = $request->boolean('ativo', true);

        return $dados;
    }

    private function salvarFoto(Request $request, Pessoa $pessoa): void
    {
        $request->validate(['foto' => ['nullable', 'image', 'max:4096']]);

        if ($request->hasFile('foto')) {
            $pessoa->update(['foto' => $request->file('foto')->store('pessoas', 'public')]);
        }
    }

    /** Salva os beneficios sociais informados (tipos cadastrados ou livres). */
    private function salvarBeneficios(Request $request, Pessoa $pessoa): void
    {
        $beneficios = $request->input('beneficios', []);
        $pessoa->beneficios()->delete();

        foreach ($beneficios as $b) {
            $temTipo = ! empty($b['tipo_beneficio_id']);
            $temLivre = ! empty($b['tipo_livre']);
            if (! $temTipo && ! $temLivre) {
                continue;
            }

            $pessoa->beneficios()->create([
                'tipo_beneficio_id' => $b['tipo_beneficio_id'] ?? null,
                'tipo_livre' => $b['tipo_livre'] ?? null,
                'valor' => $b['valor'] ?? null,
                'numero_nis' => $b['numero_nis'] ?? null,
                'observacao' => $b['observacao'] ?? null,
                'ativo' => true,
            ]);
        }
    }
}
