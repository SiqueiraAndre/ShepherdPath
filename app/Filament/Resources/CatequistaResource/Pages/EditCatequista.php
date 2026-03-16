<?php

namespace App\Filament\Resources\CatequistaResource\Pages;

use App\Filament\Resources\CatequistaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatequista extends EditRecord
{
    protected static string $resource = CatequistaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
