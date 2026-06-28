<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\InventoryCount;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\StockLevel;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMovementTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_received_increases_stock(): void
    {
        [$company, $warehouse, $product] = $this->stockContext();
        $order = PurchaseOrder::create(['company_id' => $company->id, 'warehouse_id' => $warehouse->id, 'supplier_id' => null, 'status' => 'confirmed']);
        $order->lines()->create(['product_id' => $product->id, 'quantity' => 5, 'unit_price' => 10, 'tax_rate' => 20]);

        app(StockService::class)->receivePurchase($order);

        $this->assertSame(5.0, app(StockService::class)->available($warehouse->id, $product->id));
        $this->assertDatabaseHas('stock_movements', ['type' => 'purchase', 'product_id' => $product->id]);
    }

    public function test_sale_delivered_decreases_stock(): void
    {
        [$company, $warehouse, $product] = $this->stockContext(10);
        $order = SalesOrder::create(['company_id' => $company->id, 'warehouse_id' => $warehouse->id, 'status' => 'confirmed']);
        $order->lines()->create(['product_id' => $product->id, 'quantity' => 3, 'unit_price' => 20, 'tax_rate' => 20]);

        app(StockService::class)->deliverSale($order);

        $this->assertSame(7.0, app(StockService::class)->available($warehouse->id, $product->id));
        $this->assertDatabaseHas('stock_movements', ['type' => 'sale', 'product_id' => $product->id]);
    }

    public function test_sale_blocked_if_insufficient_stock(): void
    {
        [$company, $warehouse, $product] = $this->stockContext(2);
        $order = SalesOrder::create(['company_id' => $company->id, 'warehouse_id' => $warehouse->id, 'status' => 'confirmed']);
        $order->lines()->create(['product_id' => $product->id, 'quantity' => 3, 'unit_price' => 20, 'tax_rate' => 20]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage(StockService::INSUFFICIENT_STOCK_MESSAGE);

        app(StockService::class)->deliverSale($order);
    }

    public function test_inventory_validation_adjusts_stock(): void
    {
        [$company, $warehouse, $product] = $this->stockContext(8);
        $count = InventoryCount::create(['company_id' => $company->id, 'warehouse_id' => $warehouse->id, 'status' => 'draft']);
        $count->lines()->create(['product_id' => $product->id, 'counted_quantity' => 11]);

        app(StockService::class)->validateInventory($count);

        $this->assertSame(11.0, app(StockService::class)->available($warehouse->id, $product->id));
        $this->assertDatabaseHas('stock_movements', ['type' => 'adjustment', 'product_id' => $product->id]);
    }

    private function stockContext(float $quantity = 0): array
    {
        $company = Company::create(['name' => 'A', 'subscription_plan' => 'pro']);
        $warehouse = Warehouse::create(['company_id' => $company->id, 'name' => 'Depot']);
        $product = Product::create(['company_id' => $company->id, 'name' => 'Produit', 'unit' => 'piece', 'purchase_price' => 10, 'sale_price' => 20]);

        if ($quantity > 0) {
            StockLevel::create(['company_id' => $company->id, 'warehouse_id' => $warehouse->id, 'product_id' => $product->id, 'quantity' => $quantity]);
        }

        return [$company, $warehouse, $product];
    }
}
