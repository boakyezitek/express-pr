<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PaymentDeposite extends Model
{
    use HasFactory;

    public function depositeBy():MorphTo
    {
        return $this->morphTo();
    }
}
