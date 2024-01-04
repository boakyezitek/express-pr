<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $cast = [
        'active_properties' => 'integer',
    ];

    public function account():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function avatar():MorphOne
    {
        return $this->morphOne(Image::class, 'image');
    }

    public function pitureId():MorphOne
    {
        return $this->morphOne(Image::class, 'image');
    }
}
