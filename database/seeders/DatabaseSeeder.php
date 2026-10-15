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
        $adminEmail = trim((string) env('ADMIN_EMAIL'));
        $adminPassword = (string) env('ADMIN_PASSWORD');

        if ($adminEmail === '' || $adminPassword === '') {
            throw new \RuntimeException('ADMIN_EMAIL dan ADMIN_PASSWORD wajib diisi sebelum menjalankan DatabaseSeeder.');
        }

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin Vantara Production',
                'password' => bcrypt($adminPassword),
                'is_admin' => true,
            ]
        );

        $this->call([
            PackageSeeder::class,
            SiteSettingSeeder::class,
        ]);
    }
}
