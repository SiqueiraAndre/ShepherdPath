<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catequista extends Model
{
    protected $fillable = ['nomes', 'etapa_id'];

    public function etapa()
    {
        return $this->belongsTo(Etapa::class);
    }

    public function catequizandos()
    {
        return $this->hasMany(Catequizando::class);
    }
}
