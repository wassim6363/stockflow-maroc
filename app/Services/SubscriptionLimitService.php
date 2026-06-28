<?php

namespace App\Services;

use App\Models\Company;

class SubscriptionLimitService
{
    public const LIMIT_MESSAGE = 'Vous avez atteint la limite de votre abonnement. Veuillez passer a un plan superieur.';

    public function assertCanCreateProduct(Company $company): void
    {
        $this->assertWithinLimit($company, 'products', $company->products()->count());
    }

    public function assertCanCreateWarehouse(Company $company): void
    {
        $this->assertWithinLimit($company, 'warehouses', $company->warehouses()->count());
    }

    private function assertWithinLimit(Company $company, string $key, int $current): void
    {
        $limit = config("stockflow.plans.{$company->subscription_plan}.{$key}");

        if ($limit !== null && $current >= $limit) {
            throw new \DomainException(self::LIMIT_MESSAGE);
        }
    }
}
