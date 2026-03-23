<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catequizando extends Model
{
    protected $fillable = ['nome_completo', 'etapa_id', 'catequista_id'];

    public function etapa()
    {
        return $this->belongsTo(Etapa::class);
    }

    public function catequista()
    {
        return $this->belongsTo(Catequista::class);
    }

    public function presencas()
    {
        return $this->hasMany(Presenca::class);
    }
}
