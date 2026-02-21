<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin; // <-- Namespace BARU di Filament v4
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema; // <-- Di v4 menggunakan Schema
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getUsernameFormComponent(): TextInput
    {
        return TextInput::make('username')
            ->label('Username')
            ->required()
            ->autocomplete('username')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    // Supaya kalau salah password, error-nya merah di kolom Username (bukan kolom email yang sudah tidak ada)
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('Username atau password salah'),
        ]);
    }
}