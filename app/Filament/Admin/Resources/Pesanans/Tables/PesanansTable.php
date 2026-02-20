<?php

namespace App\Filament\Admin\Resources\Pesanans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PesanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_user')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id_kasir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nomor_invoice')
                    ->searchable(),
                TextColumn::make('total_harga')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status_pembayaran')
                    ->badge(),
                TextColumn::make('status_pesanan')
                    ->badge(),
                TextColumn::make('tipe_pesanan')
                    ->badge(),
                TextColumn::make('metode_pembayaran')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
