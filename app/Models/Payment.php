<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $cast = [
        'payment_amount' => 'integer',
    ];

    public function confirmedBy():BelongsTo
    {
        return $this->belongsTo(Staff::class, 'confirmed_by');
    }

    public function tenant():BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function createdBy():BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function property():BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function formOfPayment():HasOne
    {
        return $this->hasOne(FormOfPayment::class);
    }

    public function proofOfPayment():MorphMany
    {
        return $this->morphMany(Document::class, 'resource');
    }

    public function DepositeBy():MorphTo
    {
        return $this->morphTo(PaymentDeposite::class, 'deposite_by');
    }
}
