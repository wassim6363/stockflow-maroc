<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesOrderResource\Pages\ManageSalesOrders;
use App\Models\SalesOrder;
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

class SalesOrderResource extends Resource
{
    protected static ?string $model = SalesOrder::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;
    protected static string|\UnitEnum|null $navigationGroup = 'Achats & Ventes';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Bon de vente';
    protected static ?string $pluralModelLabel = 'Ventes';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('company_id')->relationship('company', 'name')->required()->visible(fn () => auth()->user()?->isSuperAdmin()),
            Select::make('customer_id')->label('Client')->relationship('customer', 'name')->searchable()->preload(),
            Select::make('warehouse_id')->label('Entrepôt')->relationship('warehouse', 'name')->required()->searchable()->preload(),
            TextInput::make('reference')->label('Référence')->disabled()->dehydrated(false),
            DatePicker::make('sale_date')->label('Date')->default(now())->required(),
            Select::make('status')->label('Statut')->options(['draft' => 'Brouillon', 'confirmed' => 'Confirmé', 'delivered' => 'Livré', 'cancelled' => 'Annulé'])->default('draft')->required(),
            Select::make('payment_method')->label('Paiement')->options(['cash' => 'Espèce', 'bank_transfer' => 'Virement', 'check' => 'Chèque', 'card' => 'Carte', 'credit' => 'Crédit', 'other' => 'Autre']),
            TextInput::make('paid_amount')->label('Montant payé')->numeric()->minValue(0)->default(0),
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
            TextColumn::make('customer.name')->label('Client'),
            TextColumn::make('warehouse.name')->label('Entrepôt'),
            TextColumn::make('status')->label('Statut')->badge()->color(fn (string $state): string => match ($state) {
                'draft' => 'gray',
                'confirmed' => 'primary',
                'delivered' => 'success',
                'cancelled' => 'danger',
                default => 'gray',
            }),
            TextColumn::make('payment_status')->label('Paiement')->badge()->color(fn (string $state): string => match ($state) {
                'unpaid' => 'danger',
                'partial' => 'warning',
                'paid' => 'success',
                default => 'gray',
            }),
            TextColumn::make('total_ttc')->label('Total TTC')->money('MAD'),
            TextColumn::make('sale_date')->label('Date')->date('d/m/Y'),
        ])->recordActions([
            Action::make('deliver')->label('Livrer')->color('success')->visible(fn (SalesOrder $record) => $record->status !== 'delivered')
                ->action(function (SalesOrder $record): void {
                    try {
                        app(StockService::class)->deliverSale($record, auth()->id());
                        Notification::make()->title('Vente livrée et stock diminué.')->success()->send();
                    } catch (\DomainException $exception) {
                        Notification::make()->title($exception->getMessage())->danger()->send();
                    }
                }),
            Action::make('pdf')->label('PDF')->url(fn (SalesOrder $record) => route('pdf.sale', $record))->openUrlInNewTab(),
            EditAction::make(),
            DeleteAction::make(),
        ])->toolbarActions([CreateAction::make(), BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageSalesOrders::route('/')];
    }
}
