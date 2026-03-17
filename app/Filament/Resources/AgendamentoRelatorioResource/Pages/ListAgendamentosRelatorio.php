<?php

namespace App\Filament\Resources\AgendamentoRelatorioResource\Pages;

use App\Filament\Resources\AgendamentoRelatorioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgendamentosRelatorio extends ListRecords
{
    protected static string $resource = AgendamentoRelatorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo Agendamento'),
        ];
    }
}
