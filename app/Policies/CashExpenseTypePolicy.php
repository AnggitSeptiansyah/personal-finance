<?php

namespace App\Policies;

use App\Models\CashExpenseType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CashExpenseTypePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CashExpenseType $cashExpenseType): bool
    {
        return $user->id == $cashExpenseType->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CashExpenseType $cashExpenseType): bool
    {
        return $user->id == $cashExpenseType->user->id;
    }
}
