<?php

namespace App\Models\Concerns;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany(): void
    {
        static::creating(function ($model): void {
            if (! $model->company_id && auth()->check() && ! auth()->user()->isSuperAdmin()) {
                $model->company_id = auth()->user()->company_id;
            }
        });

        static::addGlobalScope('company', function (Builder $builder): void {
            if (! auth()->check() || auth()->user()->isSuperAdmin()) {
                return;
            }

            $builder->where($builder->getModel()->getTable() . '.company_id', auth()->user()->company_id);
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
