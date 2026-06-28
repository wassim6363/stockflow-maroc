<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseResource\Pages\ManageWarehouses;
use App\Models\Warehouse;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;
    protected static string|\UnitEnum|null $navigationGroup = 'Stock';
    protected static ?int $navigationSort = 30;
    protected static ?string $modelLabel = 'Entrepôt';
    protected static ?string $pluralModelLabel = 'Entrepôts';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('company_id')->relationship('company', 'name')->required()->visible(fn () => auth()->user()?->isSuperAdmin()),
            TextInput::make('name')->label('Nom')->required(),
            TextInput::make('code')->label('Code'),
            Textarea::make('address')->label('Adresse')->columnSpanFull(),
            Toggle::make('is_default')->label('Entrepot par defaut'),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Entrepôt')->searchable()->sortable(),
            TextColumn::make('code')->label('Code'),
            IconColumn::make('is_default')->label('Defaut')->boolean(),
            IconColumn::make('is_active')->label('Actif')->boolean(),
        ])->recordActions([EditAction::make(), DeleteAction::make()])
          ->toolbarActions([CreateAction::make(), BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageWarehouses::route('/')];
    }
}
