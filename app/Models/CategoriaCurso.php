<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaCurso extends Model
{
    protected $table = 'categorias_curso';

    protected $guarded = [];

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class, 'categoria_curso_id');
    }
}
