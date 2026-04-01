<?php

namespace App\Policies;

use App\Models\BankIncomeType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BankIncomeTypePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BankIncomeType $bankIncomeType): bool
    {
        return $user->id == $bankIncomeType->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BankIncomeType $bankIncomeType): bool
    {
        return $user->id == $bankIncomeType->user->id;
    }
}
