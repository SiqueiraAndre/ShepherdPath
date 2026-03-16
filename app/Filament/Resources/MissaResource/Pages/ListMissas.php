<?php

namespace App\Filament\Resources\MissaResource\Pages;

use App\Filament\Resources\MissaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMissas extends ListRecords
{
    protected static string $resource = MissaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
