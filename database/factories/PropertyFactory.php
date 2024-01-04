<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'monthly_rent_wanted' => $this->faker->randomNumber(4, true),
            'min_security_deposit' => $this->faker->randomNumber(3, true),
            'min_lease_term' => $this->faker->numberBetween(6, 13),
            'max_lease_term' => $this->faker->numberBetween(6, 13),
            'bedroom' => $this->faker->numberBetween(1, 9),
            'bath_full' => $this->faker->numberBetween(0, 9),
            'bath_half' => $this->faker->numberBetween(0, 9),
            'size' => $this->faker->numberBetween(3000, 5000),
            'street_address' => $this->faker->secondaryAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zipcode' => $this->faker->postcode(),
            'furnished' => Property::FURNISHED_YES,
            'featured' => Property::FEATURED_NO,
            'parking_spaces' => Property::PARKING_YES,
            'parking_number' => $this->faker->numerify('parking-####'),
            'parking_fees' => $this->faker->randomNumber(1, true),
            'is_lease_the_start_date_and_end_date' => Property::IS_LEASE_YES,
            'lease_start_date' => now()->addDay(5)->format('Y-m-d'),
            'lease_end_date' => now()->addDay(5)->addYear()->format('Y-m-d'),
            'available_property_date' => now()->addDay(1)->format('Y-m-d'),
            'lat' => $this->faker->latitude($min = -90, $max = 90),
            'lng' => $this->faker->longitude($min = -180, $max = 180),
            'status' => Property::PROPERTY_AVAILABLE,
        ];
    }
}
