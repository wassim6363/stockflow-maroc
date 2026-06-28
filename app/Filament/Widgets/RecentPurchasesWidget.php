<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentPurchasesWidget extends TableWidget
{
    protected static ?string $heading = 'Derniers achats';
    protected static bool $isLazy = false;

    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = ['default' => 'full', 'xl' => 1];

    protected function getTableQuery(): Builder
    {
        return PurchaseOrder::query()->latest()->limit(5);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('reference')->label('Référence')->searchable(),
                TextColumn::make('supplier.name')->label('Fournisseur')->placeholder('Non renseigné'),
                TextColumn::make('status')->label('Statut')->badge()->color(fn (string $state): string => match ($state) {
                    'draft' => 'gray',
                    'confirmed' => 'primary',
                    'received' => 'success',
                    'cancelled' => 'danger',
                    default => 'gray',
                }),
                TextColumn::make('total_ttc')->label('Total')->money('MAD'),
            ])
            ->paginated(false)
            ->emptyStateHeading('Aucune donnée pour le moment')
            ->emptyStateDescription('Les derniers achats apparaîtront ici.');
    }
}
