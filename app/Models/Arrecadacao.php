<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Arrecadacao extends Model
{
    protected $table = 'arrecadacoes';

    protected $guarded = [];

    protected $casts = [
        'data' => 'date',
        'total_dinheiro' => 'decimal:2',
        'encerrada' => 'boolean',
    ];

    public function voluntario(): BelongsTo
    {
        return $this->belongsTo(Voluntario::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doacoes(): HasMany
    {
        return $this->hasMany(Doacao::class);
    }
}
