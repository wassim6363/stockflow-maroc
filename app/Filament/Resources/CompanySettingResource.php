<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanySettingResource\Pages\ManageCompanySettings;
use App\Models\Company;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompanySettingResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static string|\UnitEnum|null $navigationGroup = 'Administration';
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationLabel = 'Paramètres';
    protected static ?string $modelLabel = 'Paramètres société';
    protected static ?string $pluralModelLabel = 'Paramètres société';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return auth()->user()?->isSuperAdmin()
            ? $query
            : $query->whereKey(auth()->user()?->company_id);
    }

    public static function canCreate(): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nom société')->required(),
            TextInput::make('ice')->label('ICE'),
            TextInput::make('rc')->label('RC'),
            TextInput::make('email')->email(),
            TextInput::make('phone')->label('Telephone'),
            TextInput::make('city')->label('Ville'),
            Textarea::make('address')->label('Adresse')->columnSpanFull(),
            FileUpload::make('logo_path')->label('Logo')->image()->directory('company-logos'),
            TextInput::make('currency')->label('Devise')->default('MAD')->maxLength(3),
            TextInput::make('default_tax_rate')->label('TVA par defaut')->numeric()->default(20),
            Toggle::make('allow_negative_stock')->label('Autoriser stock negatif')->disabled(),
            Textarea::make('invoice_footer_text')->label('Texte pied facture')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Société'),
                TextColumn::make('ice')->label('ICE'),
                TextColumn::make('currency')->label('Devise'),
                TextColumn::make('default_tax_rate')->label('TVA'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageCompanySettings::route('/')];
    }
}
