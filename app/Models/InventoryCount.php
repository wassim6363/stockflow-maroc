<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Services\ReferenceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryCount extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'warehouse_id', 'reference', 'status', 'count_date', 'notes', 'user_id'];
    protected $casts = ['count_date' => 'date'];

    protected static function booted(): void
    {
        static::creating(function (self $count): void {
            $count->reference ??= app(ReferenceService::class)->next('inventory', $count->company_id);
            $count->count_date ??= now()->toDateString();
        });
    }

    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function lines(): HasMany { return $this->hasMany(InventoryCountLine::class); }
}
