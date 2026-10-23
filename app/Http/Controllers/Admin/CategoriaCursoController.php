<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaCurso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoriaCursoController extends Controller
{
    /** Donos criam novas categorias de curso quando quiserem. */
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'icone' => ['nullable', 'string', 'max:20'],
        ]);

        CategoriaCurso::create($dados);

        return back()->with('sucesso', 'Categoria de curso criada!');
    }

    public function destroy(CategoriaCurso $categoria): RedirectResponse
    {
        $categoria->delete();

        return back()->with('sucesso', 'Categoria removida.');
    }
}
