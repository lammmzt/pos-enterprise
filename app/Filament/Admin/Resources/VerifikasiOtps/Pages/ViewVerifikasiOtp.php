<?php

namespace App\Filament\Admin\Resources\VerifikasiOtps\Pages;

use App\Filament\Admin\Resources\VerifikasiOtps\VerifikasiOtpResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVerifikasiOtp extends ViewRecord
{
    protected static string $resource = VerifikasiOtpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
