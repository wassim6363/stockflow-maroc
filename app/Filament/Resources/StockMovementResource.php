<?php

namespace App\Filament\Resources;

use App\Exports\StockMovementExport;
use App\Filament\Resources\StockMovementResource\Pages\ManageStockMovements;
use App\Models\StockMovement;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;
    protected static string|\UnitEnum|null $navigationGroup = 'Rapports';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Mouvement de stock';
    protected static ?string $pluralModelLabel = 'Mouvements de stock';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('created_at')->label('Date')->dateTime('d/m/Y H:i')->sortable(),
            TextColumn::make('product.name')->label('Produit')->searchable(),
            TextColumn::make('warehouse.name')->label('Entrepôt'),
            TextColumn::make('type')->label('Type')->badge()->color(fn (string $state): string => match ($state) {
                'purchase', 'initial', 'return_in', 'transfer_in' => 'success',
                'sale', 'return_out', 'transfer_out' => 'danger',
                'adjustment' => 'warning',
                default => 'gray',
            }),
            TextColumn::make('quantity')->label('Quantité'),
            TextColumn::make('unit_cost')->label('Cout')->money('MAD'),
            TextColumn::make('notes')->label('Notes')->limit(30),
        ])->filters([
            SelectFilter::make('type')->options([
                'initial' => 'Initial', 'purchase' => 'Achat', 'sale' => 'Vente',
                'adjustment' => 'Ajustement', 'transfer_in' => 'Transfert entrant',
                'transfer_out' => 'Transfert sortant', 'return_in' => 'Retour entrant', 'return_out' => 'Retour sortant',
            ]),
            Filter::make('created_at')
                ->schema([
                    DatePicker::make('from')->label('Du'),
                    DatePicker::make('until')->label('Au'),
                ])
                ->query(fn (Builder $query, array $data): Builder => $query
                    ->when($data['from'] ?? null, fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date))
                    ->when($data['until'] ?? null, fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date))),
        ])->toolbarActions([
            Action::make('export')->label('Exporter mouvements')->action(fn () => Excel::download(new StockMovementExport, 'mouvements-stockflow.xlsx')),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageStockMovements::route('/')];
    }
}
