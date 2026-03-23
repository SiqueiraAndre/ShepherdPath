<?php

namespace App\Jobs;

use App\Models\Presenca;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EnviarRelatorioPresencaJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $inicioSemana = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $fimSemana = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDays(6)->endOfDay();

        // 1. Consultar banco (semana atual)
        $presencas = Presenca::with(['catequizando.catequista', 'missa'])
            ->whereBetween('data_missa', [$inicioSemana, $fimSemana])
            ->get();

        if ($presencas->isEmpty()) {
            return;
        }

        // 2. Ordenar alfabeticamente e Agrupar por catequista
        $presencasAgrupadas = $presencas
            ->sortBy(fn($p) => optional($p->catequizando)->nome_completo)
            ->groupBy(fn($p) => optional(optional($p->catequizando)->catequista)->nomes ?? 'Sem Catequista');

        // 3. Gerar PDF
        $pdf = Pdf::loadView('relatorios.presenca', [
            'agrupamento' => $presencasAgrupadas,
            'periodo' => $inicioSemana->format('d/m/Y') . ' a ' . $fimSemana->format('d/m/Y')
        ]);

        // 4. Salvar o arquivo localmente (em um cenário real seria enviado por email/whatsapp)
        $nomeArquivo = 'presencas_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        Storage::disk('local')->put('relatorios/' . $nomeArquivo, $pdf->output());
    }
}
