<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitPrice = fake()->randomElement([2500000, 4500000, 7500000]);
        $quantity = 1;

        return [
            'order_id' => Order::factory(),
            'package_id' => Package::factory(),
            'package_name' => ucwords(fake()->words(3, true)),
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'subtotal' => $unitPrice * $quantity,
            'options_json' => null,
        ];
    }
}
