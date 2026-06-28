<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Services\SubscriptionLimitService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'name', 'code', 'address', 'is_default', 'is_active'];
    protected $casts = ['is_default' => 'boolean', 'is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $warehouse): void {
            if ($warehouse->company_id) {
                app(SubscriptionLimitService::class)->assertCanCreateWarehouse($warehouse->company);
            }
        });
    }

    public function stockLevels(): HasMany { return $this->hasMany(StockLevel::class); }
}
