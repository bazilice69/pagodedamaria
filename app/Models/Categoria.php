<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $guarded = [];

    public function itens(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function doacoes(): HasMany
    {
        return $this->hasMany(Doacao::class);
    }

    public function necessidades(): HasMany
    {
        return $this->hasMany(Necessidade::class);
    }
}
