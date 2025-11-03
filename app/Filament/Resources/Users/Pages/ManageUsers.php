<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Widgets\AiChatButton;
use App\Filament\Widgets\PatientVitalsChart;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;
   

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
