<?php

namespace App\Filament\Widgets;

use App\Models\SalesOrder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentSalesWidget extends TableWidget
{
    protected static ?string $heading = 'Dernières ventes';
    protected static bool $isLazy = false;

    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = ['default' => 'full', 'xl' => 1];

    protected function getTableQuery(): Builder
    {
        return SalesOrder::query()->latest()->limit(5);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('reference')->label('Référence')->searchable(),
                TextColumn::make('customer.name')->label('Client')->placeholder('Client comptoir'),
                TextColumn::make('status')->label('Statut')->badge()->color(fn (string $state): string => match ($state) {
                    'draft' => 'gray',
                    'confirmed' => 'primary',
                    'delivered' => 'success',
                    'cancelled' => 'danger',
                    default => 'gray',
                }),
                TextColumn::make('total_ttc')->label('Total')->money('MAD'),
            ])
            ->paginated(false)
            ->emptyStateHeading('Aucune donnée pour le moment')
            ->emptyStateDescription('Créez une vente pour alimenter ce tableau.');
    }
}
