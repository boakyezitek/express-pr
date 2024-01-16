<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Constants for staff types.
     */
    const ADMIN = 1;
    const MANAGER = 2;
    const STAFF = 3;

    /**
     * Define how certain attributes are cast when retrieved from the database.
     * 'staff_type' is cast to an integer, and 'is_visible_on_website' is cast to a boolean.
     */
    protected $cast = [
        'staff_type' => 'integer',
        'is_visible_on_website' => 'bool',
    ];

    /**
     * Define a BelongsTo relationship with the User model for the account associated with the staff.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Define a MorphOne relationship with the Image model for the avatar associated with the staff.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function avatar(): MorphOne
    {
        return $this->morphOne(Image::class, 'image');
    }

    /**
     * Define a MorphOne relationship with the payment model for the depositedBy associated with the staff.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'depositable');
    }
}
