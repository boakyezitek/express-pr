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

    public function account():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function avatar():MorphOne
    {
        return $this->morphOne(Image::class, 'image');
    }

    public function pictureId():MorphOne
    {
        return $this->morphOne(Image::class, 'image');
    }
}
