<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    use HasFactory;

    /**
     * Define a MorphTo relationship for the resource.
     * This method is used to associate the model with any type of resource using polymorphic relations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function resource(): MorphTo
    {
        return $this->morphTo();
    }
}
