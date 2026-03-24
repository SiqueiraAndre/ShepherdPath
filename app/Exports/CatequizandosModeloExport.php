<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Etapa;
use App\Models\Catequista;

class CatequizandosModeloExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new class implements FromArray, WithHeadings, WithTitle {
                public function array(): array {
                    return [
                        ['Joãozinho da Silva', '2015-05-12', 'Maria da Silva', '(11) 98765-4321', '1', '2']
                    ];
                }
                public function headings(): array {
                    return ['nome_completo', 'data_nascimento', 'nome_responsavel', 'telefone', 'etapa_id', 'catequista_id'];
                }
                public function title(): string { 
                    return 'Dados'; 
                }
            },
            new class implements FromArray, WithHeadings, WithTitle {
                public function array(): array {
                    return Etapa::select('id', 'nome')->get()->toArray();
                }
                public function headings(): array { 
                    return ['ID', 'NOME DA ETAPA']; 
                }
                public function title(): string { 
                    return 'Legendas - Etapas'; 
                }
            },
            new class implements FromArray, WithHeadings, WithTitle {
                public function array(): array {
                    return Catequista::select('id', 'nomes')->get()->toArray();
                }
                public function headings(): array { 
                    return ['ID', 'NOME DO(A) CATEQUISTA']; 
                }
                public function title(): string { 
                    return 'Legendas - Catequistas'; 
                }
            }
        ];
    }
}
