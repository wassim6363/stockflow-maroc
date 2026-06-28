<?php

namespace App\Filament\Resources\InventoryCountResource\Pages;

use App\Filament\Resources\InventoryCountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInventoryCounts extends ManageRecords
{
    protected static string $resource = InventoryCountResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
