<?php

namespace App\Filament\Admin\Resources\Produks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_kategori')
                    ->required()
                    ->numeric(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU'),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                TextInput::make('harga_dasar')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('harga_jual')
                    ->required()
                    ->numeric(),
                TextInput::make('gambar'),
                Toggle::make('status_aktif')
                    ->required(),
            ]);
    }
}
