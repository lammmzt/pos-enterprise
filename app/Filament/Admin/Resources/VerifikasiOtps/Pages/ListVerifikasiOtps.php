<?php

namespace App\Filament\Admin\Resources\VerifikasiOtps\Pages;

use App\Filament\Admin\Resources\VerifikasiOtps\VerifikasiOtpResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVerifikasiOtps extends ListRecords
{
    protected static string $resource = VerifikasiOtpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
