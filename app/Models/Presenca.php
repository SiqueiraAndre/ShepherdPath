<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presenca extends Model
{
    protected $fillable = ['catequizando_id', 'missa_id', 'data_missa'];

    protected $casts = [
        'data_missa' => 'date',
    ];

    public function catequizando()
    {
        return $this->belongsTo(Catequizando::class);
    }

    public function missa()
    {
        return $this->belongsTo(Missa::class);
    }
}
