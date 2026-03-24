<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catequizando extends Model
{
    protected $fillable = [
        'nome_completo', 
        'etapa_id', 
        'catequista_id',
        'data_nascimento',
        'telefone',
        'nome_responsavel'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function getIdadeAttribute()
    {
        return $this->data_nascimento ? $this->data_nascimento->age : null;
    }

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
