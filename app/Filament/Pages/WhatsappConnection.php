<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class WhatsappConnection extends Page
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-chat-bubble-left-right';
    }

    public static function getNavigationLabel(): string
    {
        return 'WhatsApp Connection';
    }

    public function getTitle(): string | Htmlable
    {
        return 'WhatsApp';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Administration';
    }

    protected string $view = 'filament.pages.whatsapp-connection';

    protected function getViewData(): array
    {
        return [
            'clientPlaceholder' => 'Client',
            'montantPlaceholder' => '0',
        ];
    }
}
