<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name', 'ice', 'rc', 'email', 'phone', 'address', 'city', 'logo_path',
        'subscription_plan', 'is_active', 'allow_negative_stock', 'default_tax_rate',
        'currency', 'invoice_footer_text',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'allow_negative_stock' => 'boolean',
        'default_tax_rate' => 'decimal:2',
    ];

    public function users(): HasMany { return $this->hasMany(User::class); }
    public function warehouses(): HasMany { return $this->hasMany(Warehouse::class); }
    public function products(): HasMany { return $this->hasMany(Product::class); }
}
