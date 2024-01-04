<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    /**
     * Define a MorphTo relationship for the image.
     * This method is used to associate the model with any type of image using polymorphic relations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function image(): MorphTo
    {
        return $this->morphTo();
    }
}
