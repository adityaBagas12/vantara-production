<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user for Vantara Production Portal Admin
        User::updateOrCreate(
            ['email' => 'admin@vantara.id'],
            [
                'name' => 'Admin Vantara Production',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@vantar.id'],
            [
                'name' => 'Admin Vantara Production',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        $this->call([
            PackageSeeder::class,
        ]);
    }
}
