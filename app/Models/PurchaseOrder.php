<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Services\ReferenceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'supplier_id', 'warehouse_id', 'reference', 'status', 'order_date', 'total_ht', 'tax_amount', 'total_ttc', 'notes', 'user_id'];
    protected $casts = ['order_date' => 'date', 'total_ht' => 'decimal:2', 'tax_amount' => 'decimal:2', 'total_ttc' => 'decimal:2'];

    protected static function booted(): void
    {
        static::creating(function (self $order): void {
            $order->reference ??= app(ReferenceService::class)->next('purchase', $order->company_id);
            $order->order_date ??= now()->toDateString();
        });
    }

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function lines(): HasMany { return $this->hasMany(PurchaseOrderLine::class); }
}
