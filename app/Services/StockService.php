<?php

namespace App\Services;

use App\Models\Company;
use App\Models\InventoryCount;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\StockLevel;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockService
{
    public const INSUFFICIENT_STOCK_MESSAGE = 'Stock insuffisant pour ce produit.';

    public function receivePurchase(PurchaseOrder $order, ?int $userId = null): PurchaseOrder
    {
        return DB::transaction(function () use ($order, $userId) {
            $order->load('lines.product', 'company');

            if ($order->status === 'received') {
                return $order;
            }

            $this->recalculatePurchaseTotals($order);

            foreach ($order->lines as $line) {
                $this->addStock(
                    $order->company_id,
                    $order->warehouse_id,
                    $line->product_id,
                    (float) $line->quantity,
                    'purchase',
                    (float) $line->unit_price,
                    PurchaseOrder::class,
                    $order->id,
                    $userId ?? $order->user_id,
                    "Reception {$order->reference}",
                );
            }

            $order->update(['status' => 'received']);

            return $order->refresh();
        });
    }

    public function deliverSale(SalesOrder $order, ?int $userId = null): SalesOrder
    {
        return DB::transaction(function () use ($order, $userId) {
            $order->load('lines.product', 'company');

            if ($order->status === 'delivered') {
                return $order;
            }

            $this->recalculateSalesTotals($order);

            foreach ($order->lines as $line) {
                $available = $this->available($order->warehouse_id, $line->product_id);

                if (! $order->company->allow_negative_stock && $available < (float) $line->quantity) {
                    throw new \DomainException(self::INSUFFICIENT_STOCK_MESSAGE);
                }
            }

            foreach ($order->lines as $line) {
                $this->addStock(
                    $order->company_id,
                    $order->warehouse_id,
                    $line->product_id,
                    -1 * (float) $line->quantity,
                    'sale',
                    (float) $line->product->purchase_price,
                    SalesOrder::class,
                    $order->id,
                    $userId ?? $order->user_id,
                    "Livraison {$order->reference}",
                );
            }

            $paymentStatus = (float) $order->paid_amount >= (float) $order->total_ttc
                ? 'paid'
                : ((float) $order->paid_amount > 0 ? 'partial' : 'unpaid');

            $order->update(['status' => 'delivered', 'payment_status' => $paymentStatus]);

            return $order->refresh();
        });
    }

    public function validateInventory(InventoryCount $count, ?int $userId = null): InventoryCount
    {
        return DB::transaction(function () use ($count, $userId) {
            $count->load('lines.product');

            if ($count->status === 'validated') {
                return $count;
            }

            foreach ($count->lines as $line) {
                $system = $this->available($count->warehouse_id, $line->product_id);
                $difference = (float) $line->counted_quantity - $system;

                $line->update([
                    'system_quantity' => $system,
                    'difference' => $difference,
                ]);

                if ($difference == 0.0) {
                    continue;
                }

                $this->addStock(
                    $count->company_id,
                    $count->warehouse_id,
                    $line->product_id,
                    $difference,
                    'adjustment',
                    null,
                    InventoryCount::class,
                    $count->id,
                    $userId ?? $count->user_id,
                    "Inventaire {$count->reference}",
                );
            }

            $count->update(['status' => 'validated']);

            return $count->refresh();
        });
    }

    public function available(int $warehouseId, int $productId): float
    {
        return (float) StockLevel::withoutGlobalScopes()
            ->where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->value('quantity');
    }

    public function addInitialStock(int $companyId, int $warehouseId, int $productId, float $quantity, ?int $userId = null): void
    {
        if ($quantity <= 0) {
            return;
        }

        $this->addStock($companyId, $warehouseId, $productId, $quantity, 'initial', null, null, null, $userId, 'Stock initial');
    }

    public function recalculatePurchaseTotals(PurchaseOrder $order): void
    {
        $totalHt = 0;
        $tax = 0;

        foreach ($order->lines as $line) {
            $lineHt = round((float) $line->quantity * (float) $line->unit_price, 2);
            $lineTax = round($lineHt * ((float) $line->tax_rate / 100), 2);
            $line->update(['total_ht' => $lineHt, 'total_ttc' => $lineHt + $lineTax]);
            $totalHt += $lineHt;
            $tax += $lineTax;
        }

        $order->update(['total_ht' => $totalHt, 'tax_amount' => $tax, 'total_ttc' => $totalHt + $tax]);
    }

    public function recalculateSalesTotals(SalesOrder $order): void
    {
        $totalHt = 0;
        $tax = 0;

        foreach ($order->lines as $line) {
            $lineHt = round((float) $line->quantity * (float) $line->unit_price, 2);
            $lineTax = round($lineHt * ((float) $line->tax_rate / 100), 2);
            $line->update(['total_ht' => $lineHt, 'total_ttc' => $lineHt + $lineTax]);
            $totalHt += $lineHt;
            $tax += $lineTax;
        }

        $order->update(['total_ht' => $totalHt, 'tax_amount' => $tax, 'total_ttc' => $totalHt + $tax]);
    }

    private function addStock(
        int $companyId,
        int $warehouseId,
        int $productId,
        float $quantity,
        string $type,
        ?float $unitCost,
        ?string $referenceType,
        ?int $referenceId,
        ?int $userId,
        ?string $notes,
    ): void {
        $level = StockLevel::withoutGlobalScopes()->firstOrCreate(
            ['warehouse_id' => $warehouseId, 'product_id' => $productId],
            ['company_id' => $companyId, 'quantity' => 0],
        );

        $level->increment('quantity', $quantity);

        StockMovement::create([
            'company_id' => $companyId,
            'warehouse_id' => $warehouseId,
            'product_id' => $productId,
            'type' => $type,
            'quantity' => abs($quantity),
            'unit_cost' => $unitCost,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'user_id' => $userId,
        ]);
    }
}
