<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isSuperAdmin() ? true : false;
    }

    public function viewAny(User $user): bool { return false; }
    public function view(User $user, Company $company): bool { return false; }
    public function create(User $user): bool { return false; }
    public function update(User $user, Company $company): bool { return false; }
    public function delete(User $user, Company $company): bool { return false; }
}
