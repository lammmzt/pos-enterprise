<?php

namespace App\Filament\Admin\Resources\BatchProduks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BatchProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_produk')
                    ->required()
                    ->numeric(),
                TextInput::make('id_pembelian')
                    ->numeric(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_beli')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal_kedaluwarsa'),
            ]);
    }
}
