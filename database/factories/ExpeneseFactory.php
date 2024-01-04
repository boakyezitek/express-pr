<?php

namespace Database\Factories;

use App\Models\Expenese;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expenese>
 */
class ExpeneseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_paid' => $this->faker->dateTimeThisMonth(),
            'description' => $this->faker->paragraph(),
            'expense_amount' => $this->faker->randomNumber(2, true),
            'is_reimbursement_necessary' => Expenese::REIMBURSEMENT_YES,
            'reimbursement_date' => now()->addDay(5)->format('Y-m-d'),
        ];
    }
}
