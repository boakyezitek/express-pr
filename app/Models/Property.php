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

    /**
     * Constants for property attributes.
     */
    const FURNISHED_NO = 1;
    const FURNISHED_YES = 2;

    const FEATURED_NO = 1;
    const FEATURED_YES = 2;

    const PARKING_NO = 1;
    const PARKING_YES = 2;
    const PARKING_AVAILABLE_FOR_RENT = 3;

    const IS_LEASE_NO = 1;
    const IS_LEASE_YES = 2;

    const PROPERTY_AVAILABLE = 1;
    const PROPERTY_MAINTENANCE = 2;
    const PROPERTY_RENTED = 3;
    const PROPERTY_CANCELLED = 4;

    /**
     * Define how certain attributes are cast when retrieved from the database.
     */
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

    /**
     * Define a BelongsTo relationship with the Staff model for the staff who created the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Define a BelongsTo relationship with the Client model for the client associated with the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Define a BelongsToMany relationship with the AmenityItem model for property amenities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function propertyAmenity(): BelongsToMany
    {
        return $this->belongsToMany(AmenityItem::class, 'property_amenity');
    }

    /**
     * Define a BelongsToMany relationship with the UtilityItem model for utility items included with the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function propertyUtilityIncluded(): BelongsToMany
    {
        return $this->belongsToMany(UtilityItem::class, 'property_utility_included');
    }

    /**
     * Define a BelongsTo relationship with the PropertyTypeItem model for the type of property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function propertyType(): BelongsToMany
    {
        return $this->belongsToMany(PropertyTypeItem::class, 'property_type');
    }

    /**
     * Define a BelongsToMany relationship with the ParkingItem model for parking types associated with the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function propertyParkingType(): BelongsToMany
    {
        return $this->belongsToMany(ParkingItem::class, 'property_parking_type');
    }

    /**
     * Define a MorphMany relationship with the Image model for images associated with the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'image');
    }

}
