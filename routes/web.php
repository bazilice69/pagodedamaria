<?php

use App\Http\Controllers\Admin\AprovacaoController;
use App\Http\Controllers\Admin\CampanhaController;
use App\Http\Controllers\Admin\CarteirinhaController;
use App\Http\Controllers\Admin\CategoriaCursoController;
use App\Http\Controllers\Admin\CepController;
use App\Http\Controllers\Admin\ConfiguracaoController;
use App\Http\Controllers\Admin\CursoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EquipeController;
use App\Http\Controllers\Admin\DoacaoController;
use App\Http\Controllers\Admin\EstoqueController;
use App\Http\Controllers\Admin\MatriculaController;
use App\Http\Controllers\Admin\NecessidadeController;
use App\Http\Controllers\Admin\ParceiroController;
use App\Http\Controllers\Admin\PessoaController;
use App\Http\Controllers\Admin\PresencaController;
use App\Http\Controllers\Admin\TermoController;
use App\Http\Controllers\Admin\VoluntarioController as AdminVoluntarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\PaginaController;
use App\Http\Controllers\Public\PortalController;
use App\Http\Controllers\Public\QueroAjudarController;
use App\Http\Controllers\Public\VerificacaoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AREA PUBLICA — qualquer pessoa acessa (seção 11, 15)
|--------------------------------------------------------------------------
*/
Route::get('/', [PaginaController::class, 'inicio'])->name('home');
Route::get('/sobre', [PaginaController::class, 'sobre'])->name('sobre');
Route::get('/campanhas', [PaginaController::class, 'campanhas'])->name('campanhas.index');
Route::get('/campanhas/{campanha}', [PaginaController::class, 'campanha'])->name('campanhas.show');
Route::get('/necessidades', [PaginaController::class, 'necessidades'])->name('necessidades.index');
Route::get('/como-ajudar', [PaginaController::class, 'comoAjudar'])->name('como-ajudar');
Route::get('/contato', [PaginaController::class, 'contato'])->name('contato');

// Verificacao publica da carteirinha (ao escanear o QR Code)
Route::get('/carteirinha/{codigo}', [VerificacaoController::class, 'carteirinha'])->name('carteirinha.verificar');

// Portal do Pai/Responsavel — acesso leve por codigo (sem senha)
Route::get('/portal', [PortalController::class, 'form'])->name('portal.entrar');
Route::post('/portal', [PortalController::class, 'entrar'])->name('portal.entrar.submit');
Route::get('/portal/painel', [PortalController::class, 'painel'])->name('portal.painel');
Route::post('/portal/sair', [PortalController::class, 'sair'])->name('portal.sair');

// Formulario publico "quero ser voluntario"
Route::get('/seja-voluntario', [QueroAjudarController::class, 'formVoluntario'])->name('voluntario.form');
Route::post('/seja-voluntario', [QueroAjudarController::class, 'storeVoluntario'])->name('voluntario.store');

/*
|--------------------------------------------------------------------------
| AUTENTICACAO
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| AREA ADMINISTRATIVA — login obrigatorio (seção 9, 13)
|--------------------------------------------------------------------------
*/
Route::prefix('painel')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('doacoes', DoacaoController::class);
    Route::resource('campanhas', CampanhaController::class)->parameters(['campanhas' => 'campanha']);
    Route::resource('necessidades', NecessidadeController::class);
    Route::resource('estoque', EstoqueController::class);
    Route::post('estoque/{item}/movimentar', [EstoqueController::class, 'movimentar'])->name('estoque.movimentar');
    Route::get('estoque/{item}/historico', [EstoqueController::class, 'historico'])->name('estoque.historico');

    // ===== V2 — Modulo educacional / cadastro de pessoas =====
    // Busca de CEP (proxy ViaCEP)
    Route::get('cep/{cep}', [CepController::class, 'buscar'])->name('cep.buscar');

    // Cadastro unico de pessoas (alunos, responsaveis, voluntarios, doadores...)
    Route::resource('pessoas', PessoaController::class);
    Route::get('pessoas/{pessoa}/carteirinha', [CarteirinhaController::class, 'show'])->name('pessoas.carteirinha');

    // Cursos e matriculas
    Route::resource('cursos', CursoController::class);
    Route::post('cursos/{curso}/matricular', [MatriculaController::class, 'store'])->name('cursos.matricular');
    Route::delete('cursos/{curso}/matriculas/{matricula}', [MatriculaController::class, 'destroy'])->name('cursos.matriculas.destroy');

    // Chamada / presenca
    Route::get('cursos/{curso}/presenca', [PresencaController::class, 'index'])->name('cursos.presenca');
    Route::post('cursos/{curso}/presenca', [PresencaController::class, 'store'])->name('cursos.presenca.store');
    Route::get('cursos/{curso}/relatorio', [PresencaController::class, 'relatorio'])->name('cursos.relatorio');

    // Categorias de curso (donos criam novas)
    Route::post('categorias-curso', [CategoriaCursoController::class, 'store'])->name('categorias-curso.store');
    Route::delete('categorias-curso/{categoria}', [CategoriaCursoController::class, 'destroy'])->name('categorias-curso.destroy');

    // Parceiros
    Route::resource('parceiros', ParceiroController::class)->except(['show']);

    // Termos de autorizacao (modelos + geracao auto-preenchida)
    Route::resource('termos', TermoController::class)->except(['show']);
    Route::post('termos/{termo}/gerar', [TermoController::class, 'gerar'])->name('termos.gerar');
    Route::get('termos-gerados/{gerado}/imprimir', [TermoController::class, 'imprimir'])->name('termos.imprimir');

    // Somente o circulo de confianca (presidente/vice/diretor) — gestores
    Route::middleware('role:presidente,vice,diretor')->group(function () {
        Route::resource('voluntarios', AdminVoluntarioController::class);

        // Aprovacao de candidaturas de voluntario
        Route::get('aprovacoes', [AprovacaoController::class, 'index'])->name('aprovacoes.index');
        Route::post('aprovacoes/{voluntario}/aprovar', [AprovacaoController::class, 'aprovar'])->name('aprovacoes.aprovar');
        Route::post('aprovacoes/{voluntario}/recusar', [AprovacaoController::class, 'recusar'])->name('aprovacoes.recusar');

        Route::get('configuracoes', [ConfiguracaoController::class, 'edit'])->name('configuracoes.edit');
        Route::put('configuracoes', [ConfiguracaoController::class, 'update'])->name('configuracoes.update');
    });

    // Gestao da EQUIPE e transferencia de presidencia — SO o presidente
    Route::middleware('role:presidente,admin')->group(function () {
        Route::resource('equipe', EquipeController::class)->except(['show']);
        Route::post('equipe/transferir-presidencia', [EquipeController::class, 'transferir'])->name('equipe.transferir');
    });
});
