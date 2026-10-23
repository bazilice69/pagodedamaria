<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Despesa extends Model
{
    protected $guarded = [];

    protected $casts = [
        'valor' => 'decimal:2',
        'data' => 'date',
        'paga' => 'boolean',
        'recorrente' => 'boolean',
    ];

    /** Categorias sugeridas (texto livre tambem é aceito). */
    public const CATEGORIAS = [
        'Luz', 'Água', 'Internet', 'Aluguel', 'Material', 'Alimentação',
        'Transporte', 'Mão de obra', 'Manutenção', 'Outros',
    ];

    public function registrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
