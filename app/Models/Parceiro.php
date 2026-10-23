<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
    protected $guarded = [];

    protected $casts = ['ativo' => 'boolean'];

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true)->orderBy('ordem')->orderBy('nome');
    }
}
