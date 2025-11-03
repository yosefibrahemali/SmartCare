<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class FixedNoteWidget extends Widget
{
    protected string $view = 'filament.widgets.fixed-note-widget';
    protected static ?int $sort = -10;

    // ✅ لا يمكن إغلاقها
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return true;
    }

    public static function shouldPersist(): bool
    {
        return true;
    }
}
