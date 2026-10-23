<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Movimentacao extends Model
{
    protected $table = 'movimentacoes';

    protected $guarded = [];

    protected $casts = [
        'quantidade' => 'decimal:2',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function referencia(): MorphTo
    {
        return $this->morphTo();
    }
}
