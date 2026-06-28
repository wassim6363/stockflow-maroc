<?php

namespace App\Filament\Widgets;

use App\Models\SalesOrderLine;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopProductsWidget extends TableWidget
{
    protected static ?string $heading = 'Top produits vendus';
    protected static bool $isLazy = false;

    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = ['default' => 'full', 'xl' => 1];

    protected function getTableQuery(): Builder
    {
        return SalesOrderLine::query()
            ->selectRaw('MIN(id) as id, product_id, SUM(quantity) as sold_quantity, SUM(total_ttc) as sold_total')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('sold_quantity')
            ->limit(5);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('product.name')->label('Produit')->searchable(),
                TextColumn::make('sold_quantity')->label('Qté vendue')->badge()->color('success'),
                TextColumn::make('sold_total')->label('CA')->money('MAD'),
            ])
            ->paginated(false)
            ->emptyStateHeading('Aucune donnée pour le moment')
            ->emptyStateDescription('Les meilleurs produits apparaîtront après les premières ventes.');
    }
}
