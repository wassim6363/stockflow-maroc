<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockWidget extends TableWidget
{
    protected static ?string $heading = 'Alertes stock bas';
    protected static bool $isLazy = false;

    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = ['default' => 'full', 'xl' => 1];

    protected function getTableQuery(): Builder
    {
        return Product::query()
            ->where('is_active', true)
            ->whereRaw('(select coalesce(sum(quantity), 0) from stock_levels where stock_levels.product_id = products.id) <= products.min_stock')
            ->limit(5);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('name')->label('Produit')->searchable(),
                TextColumn::make('current_stock')->label('Stock')->badge()->color('warning'),
                TextColumn::make('min_stock')->label('Minimum')->badge()->color('gray'),
            ])
            ->emptyStateHeading('Aucune donnée pour le moment')
            ->emptyStateDescription('Ajoutez vos premiers produits pour suivre les alertes de stock.')
            ->emptyStateActions([
                Action::make('add_product')->label('Ajouter un produit')->url(url('/admin/products')),
            ]);
    }
}
