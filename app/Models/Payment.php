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

    /**
     * Define how certain attributes are cast when retrieved from the database.
     * In this case, cast 'payment_amount' attribute to an integer.
     */
    protected $cast = [
        'payment_amount' => 'integer',
    ];

    /**
     * Define a BelongsTo relationship with the Staff model for the staff who confirmed the payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'confirmed_by');
    }

    /**
     * Define a BelongsTo relationship with the Tenant model for the tenant associated with the payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Define a BelongsTo relationship with the Staff model for the staff who created the payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Define a BelongsTo relationship with the Property model for the property associated with the payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * Define a HasOne relationship with the FormOfPayment model for the payment's form of payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function formOfPayment(): HasOne
    {
        return $this->hasOne(FormOfPayment::class);
    }

    /**
     * Define a MorphMany relationship with the Document model for the proof of payment associated with the payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function proofOfPayment(): MorphMany
    {
        return $this->morphMany(Document::class, 'resource');
    }

    /**
     * Define a MorphTo relationship for the entity that made the deposit associated with the payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function DepositeBy(): MorphTo
    {
        return $this->morphTo('deposite_by');
    }
}
