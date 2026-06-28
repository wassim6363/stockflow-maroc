<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Services\ReferenceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrder extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'customer_id', 'warehouse_id', 'reference', 'status', 'sale_date', 'total_ht', 'tax_amount', 'total_ttc', 'paid_amount', 'payment_status', 'payment_method', 'notes', 'user_id'];
    protected $casts = ['sale_date' => 'date', 'total_ht' => 'decimal:2', 'tax_amount' => 'decimal:2', 'total_ttc' => 'decimal:2', 'paid_amount' => 'decimal:2'];

    protected static function booted(): void
    {
        static::creating(function (self $order): void {
            $order->reference ??= app(ReferenceService::class)->next('sale', $order->company_id);
            $order->sale_date ??= now()->toDateString();
        });
    }

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function lines(): HasMany { return $this->hasMany(SalesOrderLine::class); }
}
