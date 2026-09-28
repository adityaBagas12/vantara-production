<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalPrice = fake()->randomElement([3500000, 5000000, 8000000, 12000000]);
        $status = fake()->randomElement(['pending', 'dp_received', 'confirmed', 'completed', 'cancelled']);
        $downPayment = match ($status) {
            'dp_received', 'confirmed', 'completed' => (int) ($totalPrice * 0.5),
            default => 0,
        };

        return [
            'order_code' => 'VAN-'.fake()->unique()->numerify('202609##-####'),
            'customer_name' => fake()->name(),
            'customer_phone' => '08'.fake()->numerify('##########'),
            'customer_email' => fake()->safeEmail(),
            'event_address' => fake()->address(),
            'event_date' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'event_end_date' => null,
            'notes' => fake()->optional()->sentence(),
            'total_price' => $totalPrice,
            'down_payment' => $downPayment,
            'status' => $status,
            'admin_notes' => null,
        ];
    }
}
