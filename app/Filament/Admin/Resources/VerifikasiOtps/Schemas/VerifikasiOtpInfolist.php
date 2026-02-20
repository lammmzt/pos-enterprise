<?php

namespace App\Filament\Admin\Resources\VerifikasiOtps\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VerifikasiOtpInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_user')
                    ->numeric(),
                TextEntry::make('kode_otp'),
                TextEntry::make('tipe')
                    ->badge(),
                TextEntry::make('waktu_kedaluwarsa')
                    ->dateTime(),
                IconEntry::make('status_terpakai')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
