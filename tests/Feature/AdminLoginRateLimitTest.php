<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginRateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_is_limited_per_email_and_ip(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $response = $this->post(route('admin.login.submit'), [
                'email' => 'admin@example.com',
                'password' => 'wrong-password',
            ]);

            $response->assertSessionHasErrors('email');
        }

        $limitedResponse = $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $limitedResponse->assertStatus(302);
        $limitedResponse->assertSessionHasErrors([
            'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam satu menit.',
        ]);
    }
}
