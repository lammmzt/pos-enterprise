<?php

namespace App\Filament\Admin\Resources\Pemasoks;

use App\Filament\Admin\Resources\Pemasoks\Pages\CreatePemasok;
use App\Filament\Admin\Resources\Pemasoks\Pages\EditPemasok;
use App\Filament\Admin\Resources\Pemasoks\Pages\ListPemasoks;
use App\Filament\Admin\Resources\Pemasoks\Schemas\PemasokForm;
use App\Filament\Admin\Resources\Pemasoks\Tables\PemasoksTable;
use App\Models\Pemasok;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PemasokResource extends Resource
{
    protected static ?string $model = Pemasok::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PemasokForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PemasoksTable::configure($table);
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
            'index' => ListPemasoks::route('/'),
            'create' => CreatePemasok::route('/create'),
            'edit' => EditPemasok::route('/{record}/edit'),
        ];
    }
}
