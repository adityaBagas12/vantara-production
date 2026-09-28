<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'category' => fake()->randomElement(['Live Streaming', 'Video Production', 'Dokumentasi Acara', 'Multi-Cam Setup']),
            'description' => fake()->paragraph(3),
            'items' => [
                '2x Kamera Sony FX3',
                '1x Video Mixer Roland V-1HD',
                '2x Operator Kamera',
                '1x Lighting Set Aputure',
                'Audio System & Clip-on Wireless',
            ],
            'price' => fake()->randomElement([2500000, 4500000, 7500000, 12000000, 15000000]),
            'is_featured' => fake()->boolean(30),
            'is_active' => true,
            'image_path' => null,
        ];
    }
}
