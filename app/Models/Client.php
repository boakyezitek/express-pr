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

    /**
     * Define how certain attributes are cast when retrieved from the database.
     * In this case, cast the 'active_properties' attribute to an integer.
     */
    protected $cast = [
        'active_properties' => 'integer',
    ];

    /**
     * Define a BelongsTo relationship with the User model.
     * The foreign key is set to 'user_id'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Define a MorphOne relationship for avatars using the Image model.
     * This indicates that the model can have one avatar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function avatar(): MorphOne
    {
        return $this->morphOne(Image::class, 'image');
    }

    /**
     * Define a MorphOne relationship for pictures using the Image model.
     * This indicates that the model can have one picture.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function pitureId(): MorphOne
    {
        return $this->morphOne(Image::class, 'image');
    }

}
