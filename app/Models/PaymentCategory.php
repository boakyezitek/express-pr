<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCategory extends Model
{
    use HasFactory;

    CONST NORMAL_PAYMENT = 1;
    CONST PAYMENT_FOR_ESCROW = 2;
}
