<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\SubscriptionLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaaSLimitsTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_product_limit(): void
    {
        $company = Company::create(['name' => 'Free', 'subscription_plan' => 'free']);
        $this->createProducts($company, 50);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage(SubscriptionLimitService::LIMIT_MESSAGE);

        Product::create(['company_id' => $company->id, 'name' => 'Too much', 'unit' => 'piece']);
    }

    public function test_free_warehouse_limit(): void
    {
        $company = Company::create(['name' => 'Free', 'subscription_plan' => 'free']);
        Warehouse::create(['company_id' => $company->id, 'name' => 'One']);

        $this->expectException(\DomainException::class);
        Warehouse::create(['company_id' => $company->id, 'name' => 'Two']);
    }

    public function test_starter_product_limit(): void
    {
        $company = Company::create(['name' => 'Starter', 'subscription_plan' => 'starter']);
        $this->createProducts($company, 300);

        $this->expectException(\DomainException::class);
        Product::create(['company_id' => $company->id, 'name' => 'Too much', 'unit' => 'piece']);
    }

    private function createProducts(Company $company, int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            Product::create([
                'company_id' => $company->id,
                'name' => "Produit {$i}",
                'sku' => "SKU-{$i}",
                'unit' => 'piece',
            ]);
        }
    }
}
