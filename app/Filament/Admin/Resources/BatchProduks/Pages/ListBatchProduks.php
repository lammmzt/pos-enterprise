<?php

namespace App\Filament\Admin\Resources\BatchProduks\Pages;

use App\Filament\Admin\Resources\BatchProduks\BatchProdukResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBatchProduks extends ListRecords
{
    protected static string $resource = BatchProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
