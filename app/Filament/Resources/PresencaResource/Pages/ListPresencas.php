<?php

namespace App\Filament\Resources\PresencaResource\Pages;

use App\Filament\Resources\PresencaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPresencas extends ListRecords
{
    protected static string $resource = PresencaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
