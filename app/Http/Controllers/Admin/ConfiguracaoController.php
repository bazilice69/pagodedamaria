<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfiguracaoController extends Controller
{
    public function edit(): View
    {
        return view('admin.configuracoes', ['config' => Configuracao::instancia()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $config = Configuracao::instancia();

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'historia' => ['nullable', 'string'],
            'sobre_trabalho' => ['nullable', 'string'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'max:2'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'horario_atendimento' => ['nullable', 'string', 'max:255'],
            'pix_chave' => ['nullable', 'string', 'max:255'],
            'pix_tipo' => ['nullable', 'string', 'max:30'],
            'pix_nome_recebedor' => ['nullable', 'string', 'max:255'],
            'pix_cidade' => ['nullable', 'string', 'max:255'],
            'criancas_atendidas' => ['nullable', 'integer', 'min:0'],
            'familias_atendidas' => ['nullable', 'integer', 'min:0'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $dados['logo'] = $request->file('logo')->store('associacao', 'public');
        } else {
            unset($dados['logo']);
        }

        $config->update($dados);

        return redirect()->route('admin.configuracoes.edit')->with('sucesso', 'Configurações salvas! 💜');
    }
}
