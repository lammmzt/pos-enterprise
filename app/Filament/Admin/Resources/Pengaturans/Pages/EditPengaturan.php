<?php

namespace App\Filament\Admin\Resources\Pengaturans\Pages;

use App\Filament\Admin\Resources\Pengaturans\PengaturanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPengaturan extends EditRecord
{
    protected static string $resource = PengaturanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
