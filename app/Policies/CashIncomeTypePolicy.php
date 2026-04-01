<?php

namespace App\Policies;

use App\Models\CashIncomeType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CashIncomeTypePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CashIncomeType $cashIncomeType): bool
    {
        return $user->id == $cashIncomeType->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CashIncomeType $cashIncomeType): bool
    {
        return $user->id == $cashIncomeType->user->id;
    }

}
