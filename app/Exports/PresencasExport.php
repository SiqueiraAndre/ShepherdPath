<?php

namespace App\Exports;

use App\Models\Presenca;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresencasExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected Collection $records;

    public function __construct(Collection $records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            '#',
            'Catequizando(a)',
            'Catequista',
            'Missa',
            'Data',
        ];
    }

    public function map($presenca): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            optional($presenca->catequizando)->nome_completo ?? 'N/A',
            optional(optional($presenca->catequizando)->catequista)->nomes ?? 'Sem Catequista',
            optional($presenca->missa)->descricao ?? 'N/A',
            $presenca->data_missa ? $presenca->data_missa->format('d/m/Y') : 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
