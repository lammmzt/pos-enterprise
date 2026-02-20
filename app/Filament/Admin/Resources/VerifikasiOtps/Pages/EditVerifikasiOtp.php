<?php

namespace App\Filament\Admin\Resources\VerifikasiOtps\Pages;

use App\Filament\Admin\Resources\VerifikasiOtps\VerifikasiOtpResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVerifikasiOtp extends EditRecord
{
    protected static string $resource = VerifikasiOtpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
