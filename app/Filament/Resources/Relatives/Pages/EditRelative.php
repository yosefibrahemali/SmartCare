<?php

namespace App\Filament\Resources\Relatives\Pages;

use App\Filament\Resources\Relatives\RelativeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRelative extends EditRecord
{
    protected static string $resource = RelativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
