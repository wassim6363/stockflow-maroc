<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'warehouse_id', 'product_id', 'type', 'quantity', 'unit_cost', 'reference_type', 'reference_id', 'notes', 'user_id'];
    protected $casts = ['quantity' => 'decimal:3', 'unit_cost' => 'decimal:2'];
    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
