<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_is_available_with_the_access_token(): void
    {
        $order = Order::factory()->create(['customer_name' => 'Pelanggan Rahasia']);

        $this->get(route('checkout.show', $order->access_token))
            ->assertOk()
            ->assertSee('Pelanggan Rahasia');
    }

    public function test_order_code_and_wrong_token_return_uniform_not_found(): void
    {
        $order = Order::factory()->create(['customer_name' => 'Pelanggan Rahasia']);

        foreach ([$order->order_code, str_repeat('a', 48)] as $token) {
            $this->get('/pesanan/'.$token)
                ->assertNotFound()
                ->assertDontSee('Pelanggan Rahasia')
                ->assertDontSee($order->customer_email ?? '');
        }
    }

    public function test_each_order_gets_a_unique_access_token(): void
    {
        $first = Order::factory()->create();
        $second = Order::factory()->create();

        $this->assertNotEmpty($first->access_token);
        $this->assertNotSame($first->access_token, $second->access_token);
    }
}
