<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPortalTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Vantara',
            'email' => 'admin@vantara.id',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $this->seed(PackageSeeder::class);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $loginResponse = $this->withSession(['_token' => 'test-token'])->post(route('admin.login.submit'), [
            '_token' => 'test-token',
            'email' => 'admin@vantara.id',
            'password' => 'password',
        ]);

        $loginResponse->assertRedirect(route('admin.dashboard'));

        $dashboardResponse = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Ringkasan Operasional & Penjualan');
    }

    public function test_admin_can_create_new_package(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['_token' => 'test-token'])
            ->post(route('admin.packages.store'), [
                '_token' => 'test-token',
                'name' => 'Paket Special Event Custom',
                'category' => 'Sound System',
                'price' => 4500000,
                'description' => 'Paket kustom acara besar.',
                'items' => ['Speaker 10.000 Watt', 'Mixer 32 Channel'],
                'is_featured' => '1',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('packages', [
            'name' => 'Paket Special Event Custom',
            'price' => 4500000,
        ]);
    }

    public function test_admin_can_update_order_status_and_dp(): void
    {
        $order = Order::create([
            'order_code' => 'VAN-20260926-9999',
            'customer_name' => 'Klien Event Utama',
            'customer_phone' => '081299998888',
            'event_address' => 'Ballroom Hotel Ritz',
            'event_date' => now()->addDays(20)->format('Y-m-d'),
            'total_price' => 5000000,
            'down_payment' => 0,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['_token' => 'test-token'])
            ->patch(route('admin.orders.updateStatus', $order->id), [
                '_token' => 'test-token',
                'status' => 'dp_received',
                'down_payment' => 2000000,
                'total_price' => 5000000,
                'admin_notes' => 'DP sudah masuk via BCA Admin.',
            ]);

        $response->assertRedirect(route('admin.orders.show', $order->id));

        $order->refresh();
        $this->assertEquals('dp_received', $order->status);
        $this->assertEquals(2000000, $order->down_payment);
        $this->assertEquals('DP sudah masuk via BCA Admin.', $order->admin_notes);
    }
}
