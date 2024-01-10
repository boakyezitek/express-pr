<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpensesCategory extends Model
{
    use HasFactory;

    /**
     * Constants for different expense types.
     */
    const NORMAL_EXPENSES = 1;
    const PAYMENT_FOR_ESCROW = 2;

    public function expenses():BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
