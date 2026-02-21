<?php

namespace App\Filament\Admin\Resources\Kategoris\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KategoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama kategori')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('slug')
                    ->label('Slug kategori')
                    ->required(),
                Toggle::make('status_aktif')
                    ->default(true)
                    ->label('Status kategori')
                    ->required(),
            ]);
    }
}