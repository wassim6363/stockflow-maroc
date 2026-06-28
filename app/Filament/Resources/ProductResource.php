<?php

namespace App\Filament\Resources;

use App\Exports\ProductExport;
use App\Exports\ProductTemplateExport;
use App\Filament\Resources\ProductResource\Pages\ManageProducts;
use App\Imports\ProductImport;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
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
use Maatwebsite\Excel\Facades\Excel;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;
    protected static string|\UnitEnum|null $navigationGroup = 'Stock';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Produit';
    protected static ?string $pluralModelLabel = 'Produits';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('company_id')->relationship('company', 'name')->required()->visible(fn () => auth()->user()?->isSuperAdmin()),
            TextInput::make('name')->label('Nom')->required()->maxLength(255),
            TextInput::make('sku')->label('SKU'),
            TextInput::make('barcode')->label('Code-barres'),
            Select::make('category_id')->label('Catégorie')->relationship('category', 'name')->searchable()->preload(),
            Select::make('unit')->label('Unité')->options([
                'piece' => 'Piece', 'kg' => 'Kg', 'litre' => 'Litre',
                'carton' => 'Carton', 'pack' => 'Pack', 'metre' => 'Metre',
            ])->required(),
            TextInput::make('purchase_price')->label('Prix achat')->numeric()->minValue(0)->required(),
            TextInput::make('sale_price')->label('Prix vente')->numeric()->minValue(0)->required(),
            TextInput::make('min_stock')->label('Stock minimum')->numeric()->minValue(0)->default(0),
            TextInput::make('tax_rate')->label('TVA')->numeric()->minValue(0)->default(0),
            FileUpload::make('image_path')->label('Image')->image()->directory('products'),
            Textarea::make('description')->label('Description')->columnSpanFull(),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Produit')->searchable()->sortable(),
            TextColumn::make('sku')->label('SKU')->searchable(),
            TextColumn::make('category.name')->label('Catégorie'),
            TextColumn::make('current_stock')->label('Stock')->badge(),
            TextColumn::make('min_stock')->label('Min'),
            TextColumn::make('purchase_price')->label('Achat')->money('MAD'),
            TextColumn::make('sale_price')->label('Vente')->money('MAD'),
            TextColumn::make('estimated_margin')->label('Marge')->money('MAD'),
            IconColumn::make('is_active')->label('Actif')->boolean(),
        ])->recordActions([EditAction::make(), DeleteAction::make()])
          ->toolbarActions([
              CreateAction::make(),
              Action::make('template')->label('Telecharger modele produits')->action(fn () => Excel::download(new ProductTemplateExport, 'modele-produits-stockflow.xlsx')),
              Action::make('import')->label('Importer produits')
                  ->schema([FileUpload::make('file')->label('Fichier Excel')->required()->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])->disk('local')])
                  ->action(function (array $data): void {
                      Excel::import(new ProductImport(auth()->user()->company_id, auth()->id()), storage_path('app/private/' . $data['file']));
                  }),
              Action::make('export')->label('Exporter produits')->action(fn () => Excel::download(new ProductExport, 'produits-stockflow.xlsx')),
              BulkActionGroup::make([DeleteBulkAction::make()]),
          ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageProducts::route('/')];
    }
}
