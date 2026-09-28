<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Package;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
    }

    public function test_can_add_package_to_cart_and_view_cart(): void
    {
        $package = Package::first();

        $response = $this->withSession(['_token' => 'test-token'])
            ->post(route('cart.store'), [
                '_token' => 'test-token',
                'package_id' => $package->id,
                'quantity' => 1,
            ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertEquals(1, count(session('cart')));

        $cartResponse = $this->get(route('cart.index'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee(e($package->name));
    }

    public function test_can_process_checkout_successfully(): void
    {
        $package = Package::first();
        $eventDate = now()->addDays(14)->format('Y-m-d');

        $checkoutResponse = $this->withSession([
            '_token' => 'test-token',
            'cart' => [
                $package->id => [
                    'package_id' => $package->id,
                    'name' => $package->name,
                    'slug' => $package->slug,
                    'category' => $package->category,
                    'price' => $package->price,
                    'quantity' => 1,
                ],
            ],
        ])->post(route('checkout.store'), [
            '_token' => 'test-token',
            'customer_name' => 'Budi Testing',
            'customer_phone' => '081234567890',
            'customer_email' => 'budi@example.com',
            'event_address' => 'Jl. Merdeka No. 123, Jakarta',
            'event_date' => $eventDate,
            'notes' => 'Tolong tepat waktu',
        ]);

        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('VAN-', $order->order_code);
        $this->assertEquals('Budi Testing', $order->customer_name);
        $this->assertEquals('pending', $order->status);

        $checkoutResponse->assertRedirect(route('checkout.show', $order->order_code));

        $successPage = $this->get(route('checkout.show', $order->order_code));
        $successPage->assertStatus(200);
        $successPage->assertSee($order->order_code);
        $successPage->assertSee('Buka WhatsApp Admin Vantara');
    }

    public function test_cannot_checkout_if_date_is_already_booked(): void
    {
        $package = Package::first();
        $eventDate = now()->addDays(7)->format('Y-m-d');

        Order::create([
            'order_code' => 'VAN-20260926-0001',
            'customer_name' => 'Existing Customer',
            'customer_phone' => '08999999999',
            'event_address' => 'Gedung A',
            'event_date' => $eventDate,
            'total_price' => 1000000,
            'status' => 'confirmed',
        ]);

        $response = $this->withSession([
            '_token' => 'test-token',
            'cart' => [
                $package->id => [
                    'package_id' => $package->id,
                    'name' => $package->name,
                    'slug' => $package->slug,
                    'category' => $package->category,
                    'price' => $package->price,
                    'quantity' => 1,
                ],
            ],
        ])->post(route('checkout.store'), [
            '_token' => 'test-token',
            'customer_name' => 'New Customer',
            'customer_phone' => '081234567890',
            'event_address' => 'Gedung B',
            'event_date' => $eventDate,
        ]);

        $response->assertSessionHasErrors('event_date');
    }
}
