<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy extends BaseCompanyPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role, ['company_admin', 'stock_manager'], true);
    }
}
