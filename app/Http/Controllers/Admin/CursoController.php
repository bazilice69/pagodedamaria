<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaCurso;
use App\Models\Curso;
use App\Models\Pessoa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CursoController extends Controller
{
    public function index(): View
    {
        return view('admin.cursos.index', [
            'cursos' => Curso::with(['categoria', 'professor'])->withCount('matriculas')->orderBy('nome')->get(),
            'categorias' => CategoriaCurso::orderBy('nome')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.cursos.form', $this->opcoes() + ['curso' => new Curso(['ativo' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Curso::create($this->validar($request));

        return redirect()->route('admin.cursos.index')->with('sucesso', 'Curso criado! 🎓');
    }

    public function show(Curso $curso): View
    {
        $curso->load(['categoria', 'professor', 'alunos']);

        // Alunos disponiveis para matricular (que ainda nao estao no curso).
        $jaMatriculados = $curso->alunos->pluck('id');
        $disponiveis = Pessoa::alunos()->whereNotIn('id', $jaMatriculados)->orderBy('nome')->get();

        return view('admin.cursos.show', ['curso' => $curso, 'disponiveis' => $disponiveis]);
    }

    public function edit(Curso $curso): View
    {
        return view('admin.cursos.form', $this->opcoes() + ['curso' => $curso]);
    }

    public function update(Request $request, Curso $curso): RedirectResponse
    {
        $curso->update($this->validar($request));

        return redirect()->route('admin.cursos.index')->with('sucesso', 'Curso atualizado.');
    }

    public function destroy(Curso $curso): RedirectResponse
    {
        $curso->delete();

        return redirect()->route('admin.cursos.index')->with('sucesso', 'Curso removido.');
    }

    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'categoria_curso_id' => ['nullable', 'exists:categorias_curso,id'],
            'professor_id' => ['nullable', 'exists:users,id'],
            'descricao' => ['nullable', 'string'],
            'local' => ['nullable', 'string', 'max:255'],
            'dias_semana' => ['nullable', 'string', 'max:100'],
            'horario' => ['nullable', 'string', 'max:100'],
            'vagas' => ['nullable', 'integer', 'min:0'],
            'idade_min' => ['nullable', 'integer', 'min:0'],
            'idade_max' => ['nullable', 'integer', 'min:0'],
            'ativo' => ['nullable', 'boolean'],
        ]);
        $dados['ativo'] = $request->boolean('ativo', true);

        return $dados;
    }

    private function opcoes(): array
    {
        return [
            'categorias' => CategoriaCurso::orderBy('nome')->get(),
            'professores' => User::whereIn('role', [User::ROLE_PROFESSOR, User::ROLE_DIRETOR, User::ROLE_PRESIDENTE, User::ROLE_VICE])
                ->orderBy('name')->get(),
        ];
    }
}
