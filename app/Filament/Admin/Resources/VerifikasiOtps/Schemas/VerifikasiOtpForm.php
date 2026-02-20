<?php

namespace App\Filament\Admin\Resources\VerifikasiOtps\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VerifikasiOtpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                TextInput::make('kode_otp')
                    ->required(),
                Select::make('tipe')
                    ->options([
            'registrasi' => 'Registrasi',
            'reset_password' => 'Reset password',
            'login' => 'Login',
            'ganti_pin' => 'Ganti pin',
        ])
                    ->required(),
                DateTimePicker::make('waktu_kedaluwarsa')
                    ->required(),
                Toggle::make('status_terpakai')
                    ->required(),
            ]);
    }
}
