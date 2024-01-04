<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormOfPayment extends Model
{
    use HasFactory;

    /**
     * Constants for different forms of payment.
     */
    const PHYSICAL = 1;
    const ELECTRONIC = 2;
}
