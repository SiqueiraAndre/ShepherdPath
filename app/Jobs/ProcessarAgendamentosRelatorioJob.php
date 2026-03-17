<?php

namespace App\Jobs;

use App\Mail\RelatorioPresencaMail;
use App\Models\AgendamentoRelatorio;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ProcessarAgendamentosRelatorioJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $agendamentos = AgendamentoRelatorio::pendentes()->get();

        foreach ($agendamentos as $agendamento) {
            try {
                foreach ($agendamento->destinatarios as $email) {
                    Mail::to($email)->send(new RelatorioPresencaMail($agendamento));
                }

                $agendamento->update([
                    'status'     => 'enviado',
                    'enviado_em' => now(),
                    'erro'       => null,
                ]);
            } catch (Throwable $e) {
                $agendamento->update([
                    'status' => 'falhou',
                    'erro'   => $e->getMessage(),
                ]);
            }
        }
    }
}
