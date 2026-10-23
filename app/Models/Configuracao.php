<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    protected $table = 'configuracoes';

    protected $guarded = [];

    protected $casts = [
        'criancas_atendidas' => 'integer',
        'familias_atendidas' => 'integer',
    ];

    /**
     * Retorna a (unica) linha de configuracao, criando-a se necessario.
     */
    public static function instancia(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'nome' => config('app.name', 'Pagode da Dona Maria'),
        ]);
    }
}
