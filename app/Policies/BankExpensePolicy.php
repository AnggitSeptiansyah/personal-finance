<?php

namespace App\Policies;

use App\Models\BankExpense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BankExpensePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BankExpense $bankExpense): bool
    {
        return $user->id == $bankExpense->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BankExpense $bankExpense): bool
    {
        return $user->id == $bankExpense->user->id;
    }
}
