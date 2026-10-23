<?php

namespace App\Providers;

use App\Models\Configuracao;
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
    }
}
