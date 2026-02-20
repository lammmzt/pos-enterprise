<?php

namespace App\Filament\Admin\Resources\BatchProduks\Pages;

use App\Filament\Admin\Resources\BatchProduks\BatchProdukResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBatchProduk extends EditRecord
{
    protected static string $resource = BatchProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
