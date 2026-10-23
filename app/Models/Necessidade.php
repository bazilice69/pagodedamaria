<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Necessidade extends Model
{
    protected $table = 'necessidades';

    protected $guarded = [];

    protected $casts = [
        'meta' => 'decimal:2',
        'arrecadado' => 'decimal:2',
        'prazo' => 'date',
        'concluida' => 'boolean',
        'publica' => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function getProgressoAttribute(): int
    {
        if (! $this->meta || $this->meta <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->arrecadado / $this->meta) * 100));
    }

    public function getRestanteAttribute(): float
    {
        return max(0, (float) $this->meta - (float) $this->arrecadado);
    }

    public function getPrioridadeCorAttribute(): string
    {
        return match ($this->prioridade) {
            'urgente' => 'red',
            'alta' => 'orange',
            'media' => 'yellow',
            default => 'gray',
        };
    }
}
