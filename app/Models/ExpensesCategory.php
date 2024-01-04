<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesCategory extends Model
{
    use HasFactory;

    CONST NORMAL_EXPENSES = 1;
    CONST PAYMENT_FOR_ESCROW = 2;
}
