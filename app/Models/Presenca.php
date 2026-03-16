<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presenca extends Model
{
    protected $fillable = ['aluno_id', 'missa_id', 'data_missa'];

    protected $casts = [
        'data_missa' => 'date',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function missa()
    {
        return $this->belongsTo(Missa::class);
    }
}
