<?php

namespace App\Filament\Admin\Resources\Pemasoks\Pages;

use App\Filament\Admin\Resources\Pemasoks\PemasokResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPemasok extends EditRecord
{
    protected static string $resource = PemasokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
