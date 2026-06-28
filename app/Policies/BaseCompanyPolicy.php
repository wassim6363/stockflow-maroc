<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCompanyPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isSuperAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, Model $model): bool
    {
        return $this->sameCompany($user, $model);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['company_admin', 'stock_manager'], true);
    }

    public function update(User $user, Model $model): bool
    {
        return $this->sameCompany($user, $model) && in_array($user->role, ['company_admin', 'stock_manager'], true);
    }

    public function delete(User $user, Model $model): bool
    {
        return $this->sameCompany($user, $model) && $user->role === 'company_admin';
    }

    protected function sameCompany(User $user, Model $model): bool
    {
        return (int) $model->company_id === (int) $user->company_id;
    }
}
