<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Missa extends Model
{
    protected $table = 'missas';

    protected $fillable = ['descricao'];

    public function presencas()
    {
        return $this->hasMany(Presenca::class);
    }
}
