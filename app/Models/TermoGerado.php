<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TermoGerado extends Model
{
    protected $table = 'termos_gerados';

    protected $guarded = [];

    protected $casts = ['assinado_em' => 'datetime'];

    public function modelo(): BelongsTo
    {
        return $this->belongsTo(ModeloTermo::class, 'modelo_termo_id');
    }

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }
}
