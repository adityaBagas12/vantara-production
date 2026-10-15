<?php

namespace Database\Factories;

use App\Models\OperationalCost;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OperationalCost>
 */
class OperationalCostFactory extends Factory
{
    protected $model = OperationalCost::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'name' => fake()->randomElement(['Transportasi', 'Konsumsi Tim', 'Bensin']),
            'amount' => fake()->numberBetween(50000, 1000000),
        ];
    }
}
