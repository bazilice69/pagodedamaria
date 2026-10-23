<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Presenca;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PresencaController extends Controller
{
    /** Tela de chamada do curso para uma data. */
    public function index(Request $request, Curso $curso): View
    {
        $data = $request->get('data', Carbon::today()->format('Y-m-d'));

        $curso->load('alunos');

        // Presencas ja registradas nesta data (mapa pessoa_id => presente).
        $registradas = Presenca::where('curso_id', $curso->id)
            ->whereDate('data', $data)
            ->pluck('presente', 'pessoa_id');

        return view('admin.cursos.presenca', [
            'curso' => $curso,
            'data' => $data,
            'registradas' => $registradas,
        ]);
    }

    /** Salva a chamada: quem estava presente. Registra QUEM fez a chamada. */
    public function store(Request $request, Curso $curso): RedirectResponse
    {
        $dados = $request->validate([
            'data' => ['required', 'date'],
            'presentes' => ['nullable', 'array'],
        ]);

        $presentes = array_map('intval', $dados['presentes'] ?? []);

        foreach ($curso->alunos as $aluno) {
            Presenca::updateOrCreate(
                ['curso_id' => $curso->id, 'pessoa_id' => $aluno->id, 'data' => $dados['data']],
                [
                    'presente' => in_array($aluno->id, $presentes, true),
                    'registrado_por' => $request->user()->id,
                ]
            );
        }

        return redirect()->route('admin.cursos.presenca', $curso)
            ->with('sucesso', 'Chamada registrada! ✅');
    }

    /**
     * Relatorio de frequencia do curso. Respeita os dias reais em que houve
     * chamada (nao assume seg-sex). Mostra por AULA e por ALUNO (% presenca).
     */
    public function relatorio(Request $request, Curso $curso): View
    {
        $curso->load('alunos');

        // Datas em que houve chamada (ordenadas).
        $datas = Presenca::where('curso_id', $curso->id)
            ->select('data')->distinct()->orderBy('data')->pluck('data')
            ->map(fn ($d) => $d->format('Y-m-d'))->values();

        // Mapa [pessoa_id][data] => presente (bool)
        $registros = Presenca::where('curso_id', $curso->id)->get()
            ->groupBy('pessoa_id')
            ->map(fn ($grupo) => $grupo->keyBy(fn ($p) => $p->data->format('Y-m-d')));

        // Resumo por aula: quantos presentes em cada data.
        $resumoAulas = Presenca::where('curso_id', $curso->id)
            ->selectRaw('data, SUM(presente) as presentes, COUNT(*) as total')
            ->groupBy('data')->orderBy('data')->get();

        return view('admin.cursos.relatorio', [
            'curso' => $curso,
            'datas' => $datas,
            'registros' => $registros,
            'resumoAulas' => $resumoAulas,
        ]);
    }
}
