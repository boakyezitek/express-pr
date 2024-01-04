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

    CONST REIMBURSEMENT_NO = 1;
    CONST REIMBURSEMENT_YES = 2;

    protected $cast = [
        'expense_amount' => 'integer',
        'is_reimbursement_necessary' => 'integer',
    ];

    public function createdBy():BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function paidBy():BelongsTo
    {
        return $this->belongsTo(Staff::class, 'paid_by');
    }

    public function paidTo():BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'paid_to');
    }

    public function proofOfPayment():MorphMany
    {
        return $this->morphMany(Document::class, 'resource');
    }
}
