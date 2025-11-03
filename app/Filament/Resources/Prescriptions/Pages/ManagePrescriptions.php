<?php

namespace App\Filament\Resources\Prescriptions\Pages;

use App\Filament\Resources\Prescriptions\PrescriptionResource;
use App\Filament\Widgets\AiChatButton;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePrescriptions extends ManageRecords
{
    protected static string $resource = PrescriptionResource::class;

    //protected static ?string $navigationGroup = 'السجلات الطبية';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         AiChatButton::class,
    //     ];
    // }


}
