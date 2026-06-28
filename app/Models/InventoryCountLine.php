<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryCountLine extends Model
{
    protected $fillable = ['inventory_count_id', 'product_id', 'system_quantity', 'counted_quantity', 'difference', 'notes'];
    protected $casts = ['system_quantity' => 'decimal:3', 'counted_quantity' => 'decimal:3', 'difference' => 'decimal:3'];
    public function inventoryCount(): BelongsTo { return $this->belongsTo(InventoryCount::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
