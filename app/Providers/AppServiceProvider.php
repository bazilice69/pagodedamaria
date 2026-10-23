<?php

namespace App\Providers;

use App\Models\Configuracao;
use App\Models\Voluntario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Disponibiliza os dados da associacao em TODAS as views
        // como $assoc, para header, rodape, pix, contato etc.
        View::composer('*', function ($view) {
            try {
                $view->with('assoc', Configuracao::instancia());
            } catch (\Throwable $e) {
                // Banco ainda nao migrado — ignora silenciosamente.
                $view->with('assoc', null);
            }
        });

        // Sininho de notificacao: nº de candidaturas pendentes (so para gestores).
        View::composer('layouts.admin', function ($view) {
            $pendentes = 0;
            try {
                $user = Auth::user();
                if ($user && $user->isGestor()) {
                    $pendentes = Voluntario::pendentes()->count();
                }
            } catch (\Throwable $e) {
                // ignora se o banco ainda nao tiver a coluna
            }
            $view->with('voluntariosPendentes', $pendentes);
        });
    }
}
