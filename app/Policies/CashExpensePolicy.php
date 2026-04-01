<?php

namespace App\Policies;

use App\Models\CashExpense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CashExpensePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CashExpense $cashExpense): bool
    {
        return $user->id == $cashExpense->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CashExpense $cashExpense): bool
    {
        return $user->id == $cashExpense->user->id;
    }
}
