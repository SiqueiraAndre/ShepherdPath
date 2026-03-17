<?php

namespace App\Mail;

use App\Models\AgendamentoRelatorio;
use App\Models\Presenca;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RelatorioPresencaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AgendamentoRelatorio $agendamento
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->agendamento->assunto,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.relatorio_presenca',
            with: [
                'mensagem' => $this->agendamento->mensagem,
                'periodo'  => $this->agendamento->periodo_inicio->format('d/m/Y')
                              . ' a '
                              . $this->agendamento->periodo_fim->format('d/m/Y'),
            ],
        );
    }

    public function attachments(): array
    {
        $inicio = $this->agendamento->periodo_inicio->startOfDay();
        $fim    = $this->agendamento->periodo_fim->endOfDay();

        $presencas = Presenca::with(['aluno.catequista', 'missa'])
            ->whereBetween('data_missa', [$inicio, $fim])
            ->get();

        $agrupamento = $presencas
            ->sortBy(fn ($p) => optional($p->aluno)->nome_completo)
            ->groupBy(fn ($p) => optional(optional($p->aluno)->catequista)->nomes ?? 'Sem Catequista');

        $periodo = $inicio->format('d/m/Y') . ' a ' . $fim->format('d/m/Y');

        $pdf = Pdf::loadView('relatorios.presenca', [
            'agrupamento' => $agrupamento,
            'periodo'     => $periodo,
        ]);

        $nomeArquivo = 'relatorio_presenca_' . $inicio->format('d-m-Y') . '_a_' . $fim->format('d-m-Y') . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $nomeArquivo)
                ->withMime('application/pdf'),
        ];
    }
}
