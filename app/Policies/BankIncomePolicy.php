<?php

namespace App\Policies;

use App\Models\BankIncome;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BankIncomePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BankIncome $bankIncome): bool
    {
        return $user->id == $bankIncome->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BankIncome $bankIncome): bool
    {
        return $user->id == $bankIncome->user->id;
    }
}
