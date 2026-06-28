<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderResource\Pages\ManagePurchaseOrders;
use App\Models\PurchaseOrder;
use App\Services\StockService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;
    protected static string|\UnitEnum|null $navigationGroup = 'Achats & Ventes';
    protected static ?int $navigationSort = 20;
    protected static ?string $modelLabel = 'Bon d’achat';
    protected static ?string $pluralModelLabel = 'Achats';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('company_id')->relationship('company', 'name')->required()->visible(fn () => auth()->user()?->isSuperAdmin()),
            Select::make('supplier_id')->label('Fournisseur')->relationship('supplier', 'name')->searchable()->preload(),
            Select::make('warehouse_id')->label('Entrepôt')->relationship('warehouse', 'name')->required()->searchable()->preload(),
            TextInput::make('reference')->label('Référence')->disabled()->dehydrated(false),
            DatePicker::make('order_date')->label('Date')->default(now())->required(),
            Select::make('status')->label('Statut')->options(['draft' => 'Brouillon', 'confirmed' => 'Confirmé', 'received' => 'Réceptionné', 'cancelled' => 'Annulé'])->default('draft')->required(),
            Repeater::make('lines')->label('Lignes')->relationship('lines')->schema([
                Select::make('product_id')->label('Produit')->relationship('product', 'name')->required()->searchable()->preload(),
                TextInput::make('quantity')->label('Quantité')->numeric()->minValue(0.001)->required(),
                TextInput::make('unit_price')->label('Prix unitaire')->numeric()->minValue(0)->required(),
                TextInput::make('tax_rate')->label('TVA')->numeric()->minValue(0)->default(0),
            ])->columns(4)->columnSpanFull(),
            Textarea::make('notes')->label('Notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('reference')->label('Référence')->searchable()->sortable(),
            TextColumn::make('supplier.name')->label('Fournisseur'),
            TextColumn::make('warehouse.name')->label('Entrepôt'),
            TextColumn::make('status')->label('Statut')->badge()->color(fn (string $state): string => match ($state) {
                'draft' => 'gray',
                'confirmed' => 'primary',
                'received' => 'success',
                'cancelled' => 'danger',
                default => 'gray',
            }),
            TextColumn::make('total_ttc')->label('Total TTC')->money('MAD'),
            TextColumn::make('order_date')->label('Date')->date('d/m/Y'),
        ])->recordActions([
            Action::make('receive')->label('Réceptionner')->color('success')->visible(fn (PurchaseOrder $record) => $record->status !== 'received')
                ->action(function (PurchaseOrder $record): void {
                    app(StockService::class)->receivePurchase($record, auth()->id());
                    Notification::make()->title('Achat réceptionné et stock augmenté.')->success()->send();
                }),
            Action::make('pdf')->label('PDF')->url(fn (PurchaseOrder $record) => route('pdf.purchase', $record))->openUrlInNewTab(),
            EditAction::make(),
            DeleteAction::make(),
        ])->toolbarActions([CreateAction::make(), BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManagePurchaseOrders::route('/')];
    }
}
