<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_date' => now()->addDay(3)->format('d-m-y'),
            'payment_amount' => $this->faker->randomNumber(4, true),
            'memo' => $this->faker->paragraph(),
            'payment_form_number' => $this->faker->swiftBicNumber(),
            'date_deposited' => now()->addDay(10)->format('d-m-y'),
            'date_confirmed' => now()->addDay(12)->format('d-m-y'),
            'payment_received' => now()->addDay(15)->format('d-m-y'),
        ];
    }
}
