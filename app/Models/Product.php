<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\SubscriptionLimitService;

class Product extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'category_id', 'sku', 'barcode', 'name', 'description', 'unit',
        'purchase_price', 'sale_price', 'min_stock', 'tax_rate', 'image_path', 'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'min_stock' => 'decimal:3',
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $product): void {
            if ($product->company_id) {
                app(SubscriptionLimitService::class)->assertCanCreateProduct($product->company);
            }
        });
    }

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function stockLevels(): HasMany { return $this->hasMany(StockLevel::class); }
    public function movements(): HasMany { return $this->hasMany(StockMovement::class); }

    public function getCurrentStockAttribute(): float
    {
        return (float) $this->stockLevels()->sum('quantity');
    }

    public function getEstimatedMarginAttribute(): float
    {
        return (float) $this->sale_price - (float) $this->purchase_price;
    }
}
