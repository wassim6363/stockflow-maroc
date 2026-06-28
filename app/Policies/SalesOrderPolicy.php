<?php

namespace App\Policies;

use App\Models\SalesOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SalesOrderPolicy extends BaseCompanyPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role, ['company_admin', 'stock_manager', 'cashier'], true);
    }

    public function update(User $user, Model $salesOrder): bool
    {
        return $this->sameCompany($user, $salesOrder) && in_array($user->role, ['company_admin', 'stock_manager', 'cashier'], true);
    }
}
