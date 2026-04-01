<?php

namespace App\Policies;

use App\Models\BankExpenseType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BankExpenseTypePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BankExpenseType $bankExpenseType): bool
    {
        return $user->id == $bankExpenseType->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BankExpenseType $bankExpenseType): bool
    {
        return $user->id == $bankExpenseType->user->id;
    }
}
