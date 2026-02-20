<?php

namespace App\Filament\Admin\Resources\Pembelians\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PembelianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_pemasok')
                    ->required()
                    ->numeric(),
                TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                TextInput::make('nomor_referensi')
                    ->required(),
                TextInput::make('total_harga')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['menunggu' => 'Menunggu', 'selesai' => 'Selesai', 'batal' => 'Batal'])
                    ->default('menunggu')
                    ->required(),
                DatePicker::make('tanggal_pembelian')
                    ->required(),
            ]);
    }
}
