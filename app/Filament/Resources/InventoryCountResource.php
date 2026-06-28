<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryCountResource\Pages\ManageInventoryCounts;
use App\Models\InventoryCount;
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

class InventoryCountResource extends Resource
{
    protected static ?string $model = InventoryCount::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static string|\UnitEnum|null $navigationGroup = 'Rapports';
    protected static ?int $navigationSort = 20;
    protected static ?string $modelLabel = 'Inventaire';
    protected static ?string $pluralModelLabel = 'Inventaires';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('company_id')->relationship('company', 'name')->required()->visible(fn () => auth()->user()?->isSuperAdmin()),
            Select::make('warehouse_id')->label('Entrepôt')->relationship('warehouse', 'name')->required()->searchable()->preload(),
            TextInput::make('reference')->label('Référence')->disabled()->dehydrated(false),
            DatePicker::make('count_date')->label('Date')->default(now())->required(),
            Select::make('status')->label('Statut')->options(['draft' => 'Brouillon', 'validated' => 'Validé', 'cancelled' => 'Annulé'])->default('draft')->required(),
            Repeater::make('lines')->label('Produits comptés')->relationship('lines')->schema([
                Select::make('product_id')->label('Produit')->relationship('product', 'name')->required()->searchable()->preload(),
                TextInput::make('system_quantity')->label('Stock système')->numeric()->default(0),
                TextInput::make('counted_quantity')->label('Stock compté')->numeric()->minValue(0)->required(),
                TextInput::make('difference')->label('Différence')->numeric()->disabled()->dehydrated(false),
                TextInput::make('notes')->label('Notes'),
            ])->columns(5)->columnSpanFull(),
            Textarea::make('notes')->label('Notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('reference')->label('Référence')->searchable()->sortable(),
            TextColumn::make('warehouse.name')->label('Entrepôt'),
            TextColumn::make('status')->label('Statut')->badge()->color(fn (string $state): string => match ($state) {
                'draft' => 'gray',
                'validated' => 'success',
                'cancelled' => 'danger',
                default => 'gray',
            }),
            TextColumn::make('count_date')->label('Date')->date('d/m/Y'),
        ])->recordActions([
            Action::make('validate')->label('Valider inventaire')->color('success')->visible(fn (InventoryCount $record) => $record->status !== 'validated')
                ->action(function (InventoryCount $record): void {
                    app(StockService::class)->validateInventory($record, auth()->id());
                    Notification::make()->title('Inventaire validé et stock ajusté.')->success()->send();
                }),
            Action::make('pdf')->label('PDF')->url(fn (InventoryCount $record) => route('pdf.inventory', $record))->openUrlInNewTab(),
            EditAction::make(),
            DeleteAction::make(),
        ])->toolbarActions([CreateAction::make(), BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageInventoryCounts::route('/')];
    }
}
