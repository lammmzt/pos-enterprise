<?php

namespace App\Filament\Admin\Resources\Pemasoks\Pages;

use App\Filament\Admin\Resources\Pemasoks\PemasokResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPemasoks extends ListRecords
{
    protected static string $resource = PemasokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
