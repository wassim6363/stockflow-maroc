<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class PrinterManagement extends Page
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-printer';
    }

    public static function getNavigationLabel(): string
    {
        return 'Gestion des imprimantes';
    }

    public function getTitle(): string | Htmlable
    {
        return 'Imprimantes';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Administration';
    }

    protected string $view = 'filament.pages.printer-management';
}
