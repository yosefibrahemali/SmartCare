<?php

namespace App\Filament\Resources\MedicationReminders\Pages;

use App\Filament\Resources\MedicationReminders\MedicationReminderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMedicationReminders extends ManageRecords
{
    protected static string $resource = MedicationReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
