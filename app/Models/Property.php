<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    CONST FURNISHED_NO = 1;
    CONST FURNISHED_YES = 2;

    CONST FEATURED_NO = 1;
    CONST FEATURED_YES = 2;

    CONST PARKING_NO = 1;
    CONST PARKING_YES = 2;
    CONST PARKING_AVAILABLE_FOR_RENT = 3;

    CONST IS_LEASE_NO = 1;
    CONST IS_LEASE_YES = 2;

    CONST PROPERTY_AVAILABLE = 1;
    CONST PROPERTY_MAINTAINACE = 2;
    CONST PROPERTY_RENTED = 3;
    CONST PROPERTY_CANCELLED = 4;


    protected $cast = [
        'monthly_rent_wanted' => 'integer',
        'min_security_deposit' => 'integer',
        'min_lease_term' => 'integer',
        'max_lease_term' => 'integer',
        'bedroom' => 'integer',
        'bath_full' => 'integer',
        'bath_half' => 'integer',
        'size' => 'integer',
        'furnished' => 'integer',
        'featured' => 'integer',
        'parking_spaces' => 'integer',
        'parking_fees' => 'integer',
        'is_lease_the_start_date_and_end_date' => 'integer',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'status' => 'integer',
    ];


    public function createdBy():BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }


    public function propertyAmenity():BelongsToMany
    {
        return $this->belongsToMany(AmenityItem::class, 'amenity_item_id');
    }

    public function propertyUtilityIncluded():BelongsToMany
    {
        return $this->belongsToMany(UtilityItem::class, 'utility_item_id');
    }

    public function propertyType():BelongsTo
    {
        return $this->belongsTo(PropertyTypeItem::class, 'property_type_item_id');
    }

    public function propertyParkingType():BelongsToMany
    {
        return $this->belongsToMany(ParkingItem::class, 'parking_item_id');
    }

    public function images():MorphMany
    {
        return $this->morphMany(Image::class, 'image');
    }

}
