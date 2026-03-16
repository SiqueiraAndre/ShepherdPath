<?php

namespace App\Filament\Resources\LinkAcessoResource\Pages;

use App\Filament\Resources\LinkAcessoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLinkAcessos extends ListRecords
{
    protected static string $resource = LinkAcessoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
