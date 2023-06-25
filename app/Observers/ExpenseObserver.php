<?php

namespace App\Observers;

use App\Models\Expense;

class ExpenseObserver
{

    /**
     * Handle the Expense "creating" event.
     */
    public function creating(Expense $expense): void
    {
        $expense->user_id = auth()->user()->id;
    }

}
