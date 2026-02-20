<?php

namespace App\Filament\Admin\Resources\BatchProduks;

use App\Filament\Admin\Resources\BatchProduks\Pages\CreateBatchProduk;
use App\Filament\Admin\Resources\BatchProduks\Pages\EditBatchProduk;
use App\Filament\Admin\Resources\BatchProduks\Pages\ListBatchProduks;
use App\Filament\Admin\Resources\BatchProduks\Schemas\BatchProdukForm;
use App\Filament\Admin\Resources\BatchProduks\Tables\BatchProduksTable;
use App\Models\BatchProduk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BatchProdukResource extends Resource
{
    protected static ?string $model = BatchProduk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BatchProdukForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BatchProduksTable::configure($table);
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
            'index' => ListBatchProduks::route('/'),
            'create' => CreateBatchProduk::route('/create'),
            'edit' => EditBatchProduk::route('/{record}/edit'),
        ];
    }
}
