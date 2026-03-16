<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class LinkAcesso extends Model
{
    //
    protected $fillable = [
        'hash',
        'descricao',
        'acessos',
        'expira_em',
        'is_ativo',
    ];

    protected $casts = [
        'expira_em' => 'datetime',
        'is_ativo' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->hash)) {
                $model->hash = Str::random(8);
            }
        });
    }
}
