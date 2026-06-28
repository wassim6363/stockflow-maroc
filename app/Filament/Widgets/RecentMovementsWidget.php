<?php

namespace App\Filament\Widgets;

use App\Models\StockMovement;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentMovementsWidget extends TableWidget
{
    protected static ?string $heading = 'Derniers mouvements';
    protected static bool $isLazy = false;

    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = ['default' => 'full', 'xl' => 1];

    protected function getTableQuery(): Builder
    {
        return StockMovement::query()->latest()->limit(8);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('created_at')->label('Date')->dateTime('d/m/Y H:i'),
                TextColumn::make('product.name')->label('Produit'),
                TextColumn::make('type')->label('Type')->badge()->color(fn (string $state): string => match ($state) {
                    'purchase', 'initial', 'return_in', 'transfer_in' => 'success',
                    'sale', 'return_out', 'transfer_out' => 'danger',
                    'adjustment' => 'warning',
                    default => 'gray',
                }),
                TextColumn::make('quantity')->label('Qté')->badge()->color('gray'),
            ])
            ->emptyStateHeading('Aucune donnée pour le moment')
            ->emptyStateDescription('Les mouvements apparaîtront après les achats, ventes ou inventaires.');
    }
}
