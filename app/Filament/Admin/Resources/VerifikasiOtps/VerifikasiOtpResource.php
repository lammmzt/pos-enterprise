<?php

namespace App\Filament\Admin\Resources\VerifikasiOtps;

use App\Filament\Admin\Resources\VerifikasiOtps\Pages\CreateVerifikasiOtp;
use App\Filament\Admin\Resources\VerifikasiOtps\Pages\EditVerifikasiOtp;
use App\Filament\Admin\Resources\VerifikasiOtps\Pages\ListVerifikasiOtps;
use App\Filament\Admin\Resources\VerifikasiOtps\Pages\ViewVerifikasiOtp;
use App\Filament\Admin\Resources\VerifikasiOtps\Schemas\VerifikasiOtpForm;
use App\Filament\Admin\Resources\VerifikasiOtps\Schemas\VerifikasiOtpInfolist;
use App\Filament\Admin\Resources\VerifikasiOtps\Tables\VerifikasiOtpsTable;
use App\Models\VerifikasiOtp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VerifikasiOtpResource extends Resource
{
    protected static ?string $model = VerifikasiOtp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VerifikasiOtpForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VerifikasiOtpInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VerifikasiOtpsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVerifikasiOtps::route('/'),
            'create' => CreateVerifikasiOtp::route('/create'),
            'view' => ViewVerifikasiOtp::route('/{record}'),
            'edit' => EditVerifikasiOtp::route('/{record}/edit'),
        ];
    }
}
