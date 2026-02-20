<?php

namespace App\Filament\Admin\Resources\Testimonis\Pages;

use App\Filament\Admin\Resources\Testimonis\TestimoniResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTestimonis extends ListRecords
{
    protected static string $resource = TestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
