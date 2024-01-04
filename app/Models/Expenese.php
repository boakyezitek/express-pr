<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expenese extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Constants for reimbursement status.
     */
    const REIMBURSEMENT_NO = 1;
    const REIMBURSEMENT_YES = 2;

    /**
     * Define how certain attributes are cast when retrieved from the database.
     * In this case, cast 'expense_amount' and 'is_reimbursement_necessary' attributes to integers.
     */
    protected $cast = [
        'expense_amount' => 'integer',
        'is_reimbursement_necessary' => 'integer',
    ];

    /**
     * Define a BelongsTo relationship with the Staff model for the creator of the expense.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Define a BelongsTo relationship with the Staff model for the person who paid the expense.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'paid_by');
    }

    /**
     * Define a BelongsTo relationship with the Vendor model for the entity to which the expense is paid.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paidTo(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'paid_to');
    }

    /**
     * Define a MorphMany relationship with the Document model for the proof of payment associated with the expense.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function proofOfPayment(): MorphMany
    {
        return $this->morphMany(Document::class, 'resource');
    }
}
