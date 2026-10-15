<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hero_image_path' => null,
            'about_image_path' => null,
        ];
    }
}
