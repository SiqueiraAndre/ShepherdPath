<?php

namespace App\Imports;

use App\Models\Catequizando;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class CatequizandosImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row['nome_completo'])) {
            return null;
        }

        // Tratamento de data caso o Excel envie como string ou serial (1900 date system)
        $dataNascimento = null;
        if (!empty($row['data_nascimento'])) {
            if (is_numeric($row['data_nascimento'])) {
                $dataNascimento = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['data_nascimento'])->format('Y-m-d');
            } else {
                $dataNascimento = Carbon::parse($row['data_nascimento'])->format('Y-m-d');
            }
        }

        return new Catequizando([
            'nome_completo'    => $row['nome_completo'],
            'data_nascimento'  => $dataNascimento,
            'nome_responsavel' => $row['nome_responsavel'] ?? null,
            'telefone'         => $row['telefone'] ?? null,
            'etapa_id'         => $row['etapa_id'] ?? null,
            'catequista_id'    => $row['catequista_id'] ?? null,
        ]);
    }
}
