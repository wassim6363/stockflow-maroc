<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StockMovementPolicy extends BaseCompanyPolicy
{
    public function create(User $user): bool { return false; }
    public function update(User $user, Model $stockMovement): bool { return false; }
    public function delete(User $user, Model $stockMovement): bool { return false; }
}
