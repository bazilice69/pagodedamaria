<?php

use App\Http\Controllers\Admin\AprovacaoController;
use App\Http\Controllers\Admin\CampanhaController;
use App\Http\Controllers\Admin\ConfiguracaoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoacaoController;
use App\Http\Controllers\Admin\EstoqueController;
use App\Http\Controllers\Admin\NecessidadeController;
use App\Http\Controllers\Admin\VoluntarioController as AdminVoluntarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\PaginaController;
use App\Http\Controllers\Public\QueroAjudarController;
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
});
