<?php

namespace App\Filament\Admin\Resources\Pengaturans\Pages;

use App\Filament\Admin\Resources\Pengaturans\PengaturanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPengaturans extends ListRecords
{
    protected static string $resource = PengaturanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
