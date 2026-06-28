<?php

namespace Tests\Feature;

use App\Imports\ProductImport;
use App\Models\Company;
use App\Models\Product;
use App\Models\StockLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProductImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_product_import(): void
    {
        $company = Company::create(['name' => 'Import Co', 'subscription_plan' => 'pro']);

        (new ProductImport($company->id))->model([
            'name' => 'Produit importe',
            'sku' => 'IMP-1',
            'barcode' => '123',
            'category' => 'Cosmetiques',
            'unit' => 'piece',
            'purchase_price' => 12,
            'sale_price' => 20,
            'min_stock' => 3,
            'initial_quantity' => 0,
            'warehouse' => 'Depot principal',
        ]);

        $this->assertDatabaseHas('products', ['company_id' => $company->id, 'name' => 'Produit importe']);
        $this->assertDatabaseHas('categories', ['company_id' => $company->id, 'name' => 'Cosmetiques']);
    }

    public function test_invalid_product_import(): void
    {
        $company = Company::create(['name' => 'Import Co', 'subscription_plan' => 'pro']);

        $this->expectException(ValidationException::class);

        (new ProductImport($company->id))->model([
            'name' => '',
            'unit' => 'invalid',
            'purchase_price' => -1,
        ]);
    }

    public function test_initial_quantity_creates_stock_level(): void
    {
        $company = Company::create(['name' => 'Import Co', 'subscription_plan' => 'pro']);

        (new ProductImport($company->id))->model([
            'name' => 'Produit stocke',
            'unit' => 'piece',
            'purchase_price' => 12,
            'sale_price' => 20,
            'initial_quantity' => 15,
            'warehouse' => 'Depot initial',
        ]);

        $product = Product::where('name', 'Produit stocke')->firstOrFail();
        $this->assertSame('15.000', StockLevel::where('product_id', $product->id)->value('quantity'));
        $this->assertDatabaseHas('stock_movements', ['type' => 'initial', 'product_id' => $product->id]);
    }
}
