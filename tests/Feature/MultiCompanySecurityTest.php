<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiCompanySecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_a_cannot_see_company_b_products(): void
    {
        [$userA, $companyA, $companyB] = $this->companies();
        Product::create(['company_id' => $companyA->id, 'name' => 'A', 'unit' => 'piece']);
        Product::create(['company_id' => $companyB->id, 'name' => 'B', 'unit' => 'piece']);

        $this->actingAs($userA);

        $this->assertSame(['A'], Product::pluck('name')->all());
    }

    public function test_company_a_cannot_see_company_b_orders(): void
    {
        [$userA, $companyA, $companyB] = $this->companies();
        $warehouseA = Warehouse::create(['company_id' => $companyA->id, 'name' => 'A']);
        $warehouseB = Warehouse::create(['company_id' => $companyB->id, 'name' => 'B']);
        PurchaseOrder::create(['company_id' => $companyA->id, 'warehouse_id' => $warehouseA->id, 'reference' => 'ACH-A', 'order_date' => now()]);
        PurchaseOrder::create(['company_id' => $companyB->id, 'warehouse_id' => $warehouseB->id, 'reference' => 'ACH-B', 'order_date' => now()]);

        $this->actingAs($userA);

        $this->assertSame(['ACH-A'], PurchaseOrder::pluck('reference')->all());
    }

    public function test_super_admin_can_see_all(): void
    {
        [$userA, $companyA, $companyB] = $this->companies();
        $super = User::create(['name' => 'Super', 'email' => 'super@test.ma', 'password' => 'password', 'role' => 'super_admin']);
        Product::create(['company_id' => $companyA->id, 'name' => 'A', 'unit' => 'piece']);
        Product::create(['company_id' => $companyB->id, 'name' => 'B', 'unit' => 'piece']);

        $this->actingAs($super);

        $this->assertCount(2, Product::all());
    }

    private function companies(): array
    {
        $companyA = Company::create(['name' => 'A', 'subscription_plan' => 'pro']);
        $companyB = Company::create(['name' => 'B', 'subscription_plan' => 'pro']);
        $userA = User::create(['company_id' => $companyA->id, 'name' => 'Admin A', 'email' => 'a@test.ma', 'password' => 'password', 'role' => 'company_admin']);

        return [$userA, $companyA, $companyB];
    }
}
