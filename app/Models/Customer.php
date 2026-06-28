<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'name', 'phone', 'email', 'address', 'city', 'ice', 'balance', 'notes', 'is_active'];
    protected $casts = ['balance' => 'decimal:2', 'is_active' => 'boolean'];
}
