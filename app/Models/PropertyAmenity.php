<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PropertyAmenity extends Model
{
    use HasFactory;

    public function property():BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'offices_tags');
    }
}
