<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\StockLevel;
use App\Models\StockMovement;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockStatsOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $stockValue = StockLevel::query()
            ->join('products', 'products.id', '=', 'stock_levels.product_id')
            ->selectRaw('COALESCE(SUM(stock_levels.quantity * products.purchase_price), 0) as value')
            ->value('value');

        $lowStock = Product::query()
            ->whereRaw('(select coalesce(sum(quantity), 0) from stock_levels where stock_levels.product_id = products.id) <= products.min_stock')
            ->count();

        $monthlySales = (float) SalesOrder::whereMonth('sale_date', now()->month)->sum('total_ttc');
        $monthlyPurchases = (float) PurchaseOrder::whereMonth('order_date', now()->month)->sum('total_ttc');
        $monthlyMovements = StockMovement::whereMonth('created_at', now()->month)->count();
        $estimatedMargin = Product::query()
            ->selectRaw('COALESCE(SUM((sale_price - purchase_price) * (select coalesce(sum(quantity), 0) from stock_levels where stock_levels.product_id = products.id)), 0) as margin')
            ->value('margin');

        return [
            Stat::make('Valeur totale stock', number_format((float) $stockValue, 2, ',', ' ') . ' MAD')
                ->description('Valorisation au prix achat')
                ->descriptionIcon('heroicon-m-banknotes')
                ->icon('heroicon-m-cube')
                ->chart([7, 9, 8, 12, 15, 14, 18])
                ->color('success'),
            Stat::make('Produits actifs', Product::where('is_active', true)->count())
                ->description('Catalogue disponible')
                ->descriptionIcon('heroicon-m-tag')
                ->icon('heroicon-m-squares-2x2')
                ->chart([4, 7, 9, 9, 13, 15, 16])
                ->color('primary'),
            Stat::make('Sous stock minimum', $lowStock)
                ->description('Alertes à traiter')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->icon('heroicon-m-bell-alert')
                ->color($lowStock > 0 ? 'warning' : 'success'),
            Stat::make('Ventes du mois', number_format($monthlySales, 2, ',', ' ') . ' MAD')
                ->description('Chiffre livré ce mois')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-m-receipt-percent')
                ->chart([5, 8, 10, 9, 13, 17, 21])
                ->color('success'),
            Stat::make('Achats du mois', number_format($monthlyPurchases, 2, ',', ' ') . ' MAD')
                ->description('Approvisionnements')
                ->descriptionIcon('heroicon-m-truck')
                ->icon('heroicon-m-shopping-bag')
                ->chart([4, 6, 8, 7, 9, 11, 12])
                ->color('primary'),
            Stat::make('Mouvements du mois', $monthlyMovements)
                ->description('Entrées, sorties, ajustements')
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->icon('heroicon-m-circle-stack')
                ->color('primary'),
            Stat::make('Marge estimée stock', number_format((float) $estimatedMargin, 2, ',', ' ') . ' MAD')
                ->description('Prix vente - prix achat')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->icon('heroicon-m-presentation-chart-line')
                ->color('success'),
        ];
    }
}
