<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCategory extends Model
{
    use HasFactory;

    /**
     * Constants for different payment categories.
     */
    const NORMAL_PAYMENT = 1;
    const PAYMENT_FOR_ESCROW = 2;
}
