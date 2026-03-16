<?php

namespace App\Filament\Resources\MissaResource\Pages;

use App\Filament\Resources\MissaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMissa extends EditRecord
{
    protected static string $resource = MissaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
