<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    protected $fillable = ['nome'];

    public function catequistas()
    {
        return $this->hasMany(Catequista::class);
    }

    public function alunos()
    {
        return $this->hasMany(Aluno::class);
    }
}
