<?php

namespace App\Policies;

use App\Models\TransactionCategory;
use App\Models\User;

class TransactionCategoryPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TransactionCategory $transactionCategory): bool
    {
        return $user->id === $transactionCategory->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TransactionCategory $transactionCategory): bool
    {
        return $user->id === $transactionCategory->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TransactionCategory $transactionCategory): bool
    {
        return $user->id === $transactionCategory->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TransactionCategory $transactionCategory): bool
    {
        return $user->id === $transactionCategory->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TransactionCategory $transactionCategory): bool
    {
        return $user->id === $transactionCategory->user_id;
    }
}
