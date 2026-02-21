<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                TextInput::make('username')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['aktif' => 'Aktif', 'tidak_aktif' => 'Tidak aktif'])
                    ->default('aktif')
                    ->required(),
                TextInput::make('catatan')
                    ->required(),
            ]);
    }
}
