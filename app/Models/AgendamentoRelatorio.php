<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AgendamentoRelatorio extends Model
{
    protected $table = 'agendamentos_relatorio';

    protected $fillable = [
        'destinatarios',
        'data_envio',
        'periodo_inicio',
        'periodo_fim',
        'assunto',
        'mensagem',
        'status',
        'enviado_em',
        'erro',
    ];

    protected $casts = [
        'destinatarios'  => 'array',
        'data_envio'     => 'datetime',
        'periodo_inicio' => 'date',
        'periodo_fim'    => 'date',
        'enviado_em'     => 'datetime',
    ];

    /**
     * Agendamentos pendentes com data de envio já atingida.
     */
    public function scopePendentes(Builder $query): Builder
    {
        return $query
            ->where('status', 'pendente')
            ->where('data_envio', '<=', now());
    }
}
