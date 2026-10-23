<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voluntario extends Model
{
    protected $table = 'voluntarios';

    protected $guarded = [];

    protected $casts = [
        'areas' => 'array',
        'ativo' => 'boolean',
    ];

    public const AREAS = [
        'arrecadacao' => 'Arrecadação',
        'transporte' => 'Transporte',
        'organizacao' => 'Organização',
        'distribuicao' => 'Distribuição',
        'eventos' => 'Eventos',
        'comunicacao' => 'Comunicação',
        'outros' => 'Outros',
    ];

    public function arrecadacoes(): HasMany
    {
        return $this->hasMany(Arrecadacao::class);
    }
}
