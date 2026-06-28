<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\InventoryCount;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\Warehouse;
use App\Services\PdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_pdf_generated(): void
    {
        [$company, $warehouse, $product] = $this->context();
        $order = SalesOrder::create(['company_id' => $company->id, 'warehouse_id' => $warehouse->id]);
        $order->lines()->create(['product_id' => $product->id, 'quantity' => 1, 'unit_price' => 20, 'tax_rate' => 20, 'total_ht' => 20, 'total_ttc' => 24]);
        $order->update(['total_ht' => 20, 'tax_amount' => 4, 'total_ttc' => 24]);

        $this->assertStringStartsWith('%PDF', app(PdfService::class)->salesOrder($order));
    }

    public function test_purchase_pdf_generated(): void
    {
        [$company, $warehouse, $product] = $this->context();
        $order = PurchaseOrder::create(['company_id' => $company->id, 'warehouse_id' => $warehouse->id]);
        $order->lines()->create(['product_id' => $product->id, 'quantity' => 1, 'unit_price' => 10, 'tax_rate' => 20, 'total_ht' => 10, 'total_ttc' => 12]);
        $order->update(['total_ht' => 10, 'tax_amount' => 2, 'total_ttc' => 12]);

        $this->assertStringStartsWith('%PDF', app(PdfService::class)->purchaseOrder($order));
    }

    public function test_inventory_pdf_generated(): void
    {
        [$company, $warehouse, $product] = $this->context();
        $count = InventoryCount::create(['company_id' => $company->id, 'warehouse_id' => $warehouse->id]);
        $count->lines()->create(['product_id' => $product->id, 'system_quantity' => 5, 'counted_quantity' => 6, 'difference' => 1]);

        $this->assertStringStartsWith('%PDF', app(PdfService::class)->inventoryReport($count));
    }

    private function context(): array
    {
        $company = Company::create(['name' => 'PDF Co', 'subscription_plan' => 'pro']);
        $warehouse = Warehouse::create(['company_id' => $company->id, 'name' => 'Depot']);
        $product = Product::create(['company_id' => $company->id, 'name' => 'Produit', 'unit' => 'piece', 'purchase_price' => 10, 'sale_price' => 20]);

        return [$company, $warehouse, $product];
    }
}
