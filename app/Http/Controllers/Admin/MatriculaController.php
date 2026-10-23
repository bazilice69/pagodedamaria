<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Matricula;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    /** Matricula um aluno num curso (pessoa pode estar em varios cursos). */
    public function store(Request $request, Curso $curso): RedirectResponse
    {
        $dados = $request->validate([
            'pessoa_id' => ['required', 'exists:pessoas,id'],
        ]);

        Matricula::firstOrCreate(
            ['pessoa_id' => $dados['pessoa_id'], 'curso_id' => $curso->id],
            ['status' => 'ativa', 'data_matricula' => now()]
        );

        return back()->with('sucesso', 'Aluno matriculado no curso! 🎓');
    }

    public function destroy(Curso $curso, Matricula $matricula): RedirectResponse
    {
        $matricula->delete();

        return back()->with('sucesso', 'Matrícula removida.');
    }
}
