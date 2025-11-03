<?php

namespace App\Filament\Resources\Relatives\Pages;

use App\Filament\Resources\Relatives\RelativeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRelatives extends ListRecords
{
    protected static string $resource = RelativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
