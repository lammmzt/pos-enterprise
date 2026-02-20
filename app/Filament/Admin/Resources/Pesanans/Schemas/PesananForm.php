<?php

namespace App\Filament\Admin\Resources\Pesanans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                TextInput::make('id_kasir')
                    ->numeric(),
                TextInput::make('nomor_invoice')
                    ->required(),
                TextInput::make('total_harga')
                    ->required()
                    ->numeric(),
                Select::make('status_pembayaran')
                    ->options(['belum_bayar' => 'Belum bayar', 'lunas' => 'Lunas', 'gagal' => 'Gagal', 'refund' => 'Refund'])
                    ->default('belum_bayar')
                    ->required(),
                Select::make('status_pesanan')
                    ->options(['proses' => 'Proses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'])
                    ->default('proses')
                    ->required(),
                Select::make('tipe_pesanan')
                    ->options(['takeaway' => 'Takeaway', 'dinein' => 'Dinein', 'delivery' => 'Delivery'])
                    ->default('dinein')
                    ->required(),
                TextInput::make('metode_pembayaran'),
                Textarea::make('link_delivery')
                    ->columnSpanFull(),
                Textarea::make('catatan')
                    ->columnSpanFull(),
            ]);
    }
}
