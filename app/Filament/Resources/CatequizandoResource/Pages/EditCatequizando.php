<?php

namespace App\Filament\Resources\CatequizandoResource\Pages;

use App\Filament\Resources\CatequizandoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatequizando extends EditRecord
{
    protected static string $resource = CatequizandoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
