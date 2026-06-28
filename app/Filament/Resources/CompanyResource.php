<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages\ManageCompanies;
use App\Models\Company;
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
use Illuminate\Database\Eloquent\Builder;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;
    protected static string|\UnitEnum|null $navigationGroup = 'Administration';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Société';
    protected static ?string $pluralModelLabel = 'Sociétés';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()?->isSuperAdmin()
            ? parent::getEloquentQuery()
            : parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nom société')->required()->maxLength(255),
            TextInput::make('ice')->label('ICE'),
            TextInput::make('rc')->label('RC'),
            TextInput::make('email')->email(),
            TextInput::make('phone')->label('Telephone'),
            TextInput::make('city')->label('Ville'),
            Textarea::make('address')->label('Adresse')->columnSpanFull(),
            Select::make('subscription_plan')->label('Plan')->options([
                'free' => 'Free',
                'starter' => 'Starter - 99 DH/mois',
                'pro' => 'Pro - 199 DH/mois',
                'enterprise' => 'Enterprise - 499 DH/mois',
            ])->required(),
            TextInput::make('default_tax_rate')->label('TVA par defaut')->numeric()->default(20),
            Toggle::make('allow_negative_stock')->label('Autoriser stock negatif'),
            Toggle::make('is_active')->label('Active')->default(true),
            Textarea::make('invoice_footer_text')->label('Texte pied de facture')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Société')->searchable()->sortable(),
                TextColumn::make('city')->label('Ville'),
                TextColumn::make('subscription_plan')->label('Plan')->badge()->color(fn (string $state): string => match ($state) {
                    'free' => 'gray',
                    'starter' => 'primary',
                    'pro' => 'success',
                    'enterprise' => 'warning',
                    default => 'gray',
                }),
                IconColumn::make('is_active')->label('Active')->boolean(),
                TextColumn::make('created_at')->dateTime('d/m/Y')->label('Creation'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([CreateAction::make(), BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageCompanies::route('/')];
    }
}
