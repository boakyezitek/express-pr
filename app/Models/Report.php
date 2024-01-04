<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    use HasFactory;


    /**
     * Define a BelongsTo relationship with the Staff model for the client associated with the report.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'client_id');
    }

    /**
     * Define a MorphTo relationship for the document associated with the report.
     * This method is used to associate the model with any type of document using polymorphic relations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function report(): MorphTo
    {
        return $this->morphTo(Document::class, 'resource');
    }
}
