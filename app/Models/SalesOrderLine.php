<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderLine extends Model
{
    protected $fillable = ['sales_order_id', 'product_id', 'quantity', 'unit_price', 'tax_rate', 'total_ht', 'total_ttc'];
    protected $casts = ['quantity' => 'decimal:3', 'unit_price' => 'decimal:2', 'tax_rate' => 'decimal:2', 'total_ht' => 'decimal:2', 'total_ttc' => 'decimal:2'];
    public function order(): BelongsTo { return $this->belongsTo(SalesOrder::class, 'sales_order_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
