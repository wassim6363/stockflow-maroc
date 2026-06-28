<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Filament\Widgets\Widget;

class StockflowHeroWidget extends Widget
{
    protected string $view = 'filament.widgets.stockflow-hero';

    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $user = auth()->user();
        $company = $user?->company;

        return [
            'companyName' => $company?->name ?? 'Toutes les sociétés',
            'plan' => $company?->subscription_plan ? strtoupper($company->subscription_plan) : 'SUPER ADMIN',
            'productCount' => Product::where('is_active', true)->count(),
            'warehouseCount' => Warehouse::where('is_active', true)->count(),
            'warehouseName' => Warehouse::where('is_active', true)->value('name') ?? 'Entrepôt Principal',
            'lastLogin' => now()->format('d/m/Y à H:i'),
            'movementCount' => StockMovement::whereDate('created_at', today())->count(),
        ];
    }
}
