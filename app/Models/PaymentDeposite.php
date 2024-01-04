<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PaymentDeposite extends Model
{
    use HasFactory;

    /**
     * Define a MorphTo relationship for the entity that made the deposit associated with the payment.
     * This method is used to associate the model with any type of entity using polymorphic relations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function depositeBy(): MorphTo
    {
        return $this->morphTo();
    }
}
