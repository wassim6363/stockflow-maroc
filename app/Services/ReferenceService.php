<?php

namespace App\Services;

use App\Models\InventoryCount;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;

class ReferenceService
{
    public function next(string $type, int $companyId): string
    {
        [$prefix, $model] = match ($type) {
            'purchase' => ['ACH', PurchaseOrder::class],
            'sale' => ['VTE', SalesOrder::class],
            'inventory' => ['INV', InventoryCount::class],
            default => throw new \InvalidArgumentException("Type de reference inconnu: {$type}"),
        };

        $year = now()->year;
        $count = $model::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where('reference', 'like', "{$prefix}-{$year}-%")
            ->count() + 1;

        return sprintf('%s-%d-%04d', $prefix, $year, $count);
    }
}
