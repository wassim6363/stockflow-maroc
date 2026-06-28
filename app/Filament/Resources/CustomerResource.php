<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages\ManageCustomers;
use App\Models\Customer;
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

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static string|\UnitEnum|null $navigationGroup = 'Contacts';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Client';
    protected static ?string $pluralModelLabel = 'Clients';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('company_id')->relationship('company', 'name')->required()->visible(fn () => auth()->user()?->isSuperAdmin()),
            TextInput::make('name')->label('Nom')->required(),
            TextInput::make('phone')->label('Telephone'),
            TextInput::make('email')->email(),
            TextInput::make('city')->label('Ville'),
            TextInput::make('ice')->label('ICE'),
            TextInput::make('balance')->label('Solde')->numeric()->default(0),
            Textarea::make('address')->label('Adresse')->columnSpanFull(),
            Textarea::make('notes')->label('Notes')->columnSpanFull(),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Client')->searchable()->sortable(),
            TextColumn::make('phone')->label('Telephone'),
            TextColumn::make('city')->label('Ville'),
            TextColumn::make('balance')->label('Solde')->money('MAD'),
            IconColumn::make('is_active')->label('Actif')->boolean(),
        ])->recordActions([EditAction::make(), DeleteAction::make()])
          ->toolbarActions([CreateAction::make(), BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageCustomers::route('/')];
    }
}
