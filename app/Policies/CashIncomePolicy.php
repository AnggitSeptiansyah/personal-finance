<?php

namespace App\Policies;

use App\Models\CashIncome;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CashIncomePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CashIncome $cashIncome): bool
    {
        return $user->id == $cashIncome->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CashIncome $cashIncome): bool
    {
        return $user->id == $cashIncome->user->id;
    }
}
