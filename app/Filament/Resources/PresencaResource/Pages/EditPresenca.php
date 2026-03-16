<?php

namespace App\Filament\Resources\PresencaResource\Pages;

use App\Filament\Resources\PresencaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPresenca extends EditRecord
{
    protected static string $resource = PresencaResource::class;

    protected ?string $heading = 'Editar Presença';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
