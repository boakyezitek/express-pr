<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{

    use HasFactory, SoftDeletes;


    /**
     * Define a BelongsTo relationship with the User model for the account associated with the vendor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Define a MorphOne relationship with the Image model for the avatar associated with the vendor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function avatar(): MorphOne
    {
        return $this->morphOne(Image::class, 'image');
    }

    /**
     * Define a MorphOne relationship with the Image model for the picture ID associated with the vendor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function pictureId(): MorphOne
    {
        return $this->morphOne(Image::class, 'image');
    }
}
