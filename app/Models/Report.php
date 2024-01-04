<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    use HasFactory;


    public function client():BelongsTo
    {
        return $this->belongsTo(Staff::class, 'client_id');
    }


    public function report():MorphTo
    {
        return $this->morphTo(Document::class, 'resource');
    }
}
