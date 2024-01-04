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

    const ADMIN = 1;
    const MANAGER = 2;
    const STAFF = 3;


    protected $cast = [
        'staff_type' => 'integer',
        'is_visible_on_website' => 'bool'
    ];

    public function account():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function avatar():MorphOne
    {
        return $this->morphOne(Image::class, 'image');
    }
}
