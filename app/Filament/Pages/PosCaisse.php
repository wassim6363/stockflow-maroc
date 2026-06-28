<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Product;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PosCaisse extends Page
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-shopping-cart';
    }

    public static function getNavigationLabel(): string
    {
        return 'Caisse (POS)';
    }

    public function getTitle(): \Illuminate\Contracts\Support\Htmlable | string
    {
        return 'Caisse';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Achats & Ventes';
    }
    protected string $view = 'filament.pages.pos-caisse';
    protected static string $layout = 'filament-panels::components.layout.base';

    public $searchQuery = '';
    public $selectedCategoryId = null;

    protected function getViewData(): array
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->with(['category', 'stockLevels'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $user = Auth::user();
        $companyName = $user && $user->company ? $user->company->name : 'StockFlow Maroc';

        return [
            'categories' => $categories,
            'products' => $products,
            'companyName' => $companyName,
        ];
    }
}
