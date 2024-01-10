<?php

namespace App\Models\Validators;

use App\Models\Property;
use Illuminate\Validation\Rule;

class PropertyValidator
{
    public function validate(Property $property, array $attributes): array
    {
        return validator(
            $attributes,
            [
                'property_name' => [Rule::when($property->exists, 'sometimes'), 'required', 'string'],
                'description' => [Rule::when($property->exists, 'sometimes'), 'required', 'string'],
                'monthly_rent_wanted' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer'],
                'min_security_deposit' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer'],
                'min_lease_term' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer', 'max:12'],
                'max_lease_term' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer', 'max:12'],
                'bedroom' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer', 'max:9'],
                'bath_full' => [Rule::when($property->exists, 'sometimes'), 'required', 'max:9'],
                'bath_half' => [Rule::when($property->exists, 'sometimes'), 'required', 'max:9'],
                'size' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer'],
                'street_address' => [Rule::when($property->exists, 'sometimes'), 'required', 'string'],
                'city' => [Rule::when($property->exists, 'sometimes'), 'required', 'string'],
                'state' => [Rule::when($property->exists, 'sometimes'), 'required', 'string'],
                'zipcode' => [Rule::when($property->exists, 'sometimes'), 'required', 'string'],
                'furnished' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer'],
                'featured' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer'],
                'parking_spaces' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer'],
                'parking_number' => [Rule::when($property->exists, 'sometimes'), 'string'],
                'parking_fees' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer'],
                'is_lease_the_start_date_and_end_date' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer'],
                'lease_start_date' => [Rule::when($property->exists, 'sometimes'), 'date:Y-m-d'],
                'lease_end_date' => [Rule::when($property->exists, 'sometimes'), 'date:Y-m-d'],
                'available_property_date' => [Rule::when($property->exists, 'sometimes'), 'required', 'date:Y-m-d'],
                'lat' => [Rule::when($property->exists, 'sometimes'), 'required', 'numeric'],
                'lng' => [Rule::when($property->exists, 'sometimes'), 'required', 'numeric'],
                'client_id' => [Rule::when($property->exists, 'sometimes'), 'required', 'integer'],
                'created_by' => [Rule::when($property->exists, 'sometimes'), 'integer'],
                'property_amenity' => ['array'],
                'property_amenity.*' => ['integer', Rule::exists('amenity_items', 'id')],
                'property_utility_included' => ['array'],
                'property_utility_included.*' => ['integer', Rule::exists('utility_items', 'id')],
                'property_type' => ['array'],
                'property_type.*' => ['integer', Rule::exists('property_type_items', 'id')],
                'property_parking_type' => ['array'],
                'property_parking_type.*' => ['integer', Rule::exists('parking_items', 'id')],
            ]
        )->validate();
    }
}
