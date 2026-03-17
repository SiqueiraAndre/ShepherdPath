<?php

namespace App\Filament\Resources\AgendamentoRelatorioResource\Pages;

use App\Filament\Resources\AgendamentoRelatorioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgendamentosRelatorio extends EditRecord
{
    protected static string $resource = AgendamentoRelatorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}