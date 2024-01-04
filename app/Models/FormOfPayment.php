<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormOfPayment extends Model
{
    use HasFactory;

    CONST PHYSICAL = 1;
    CONST ELECTRONIC = 2;
}
