<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Package;
use Carbon\Carbon;
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

        $checkoutResponse->assertRedirect(route('checkout.show', $order->access_token));

        $successPage = $this->get(route('checkout.show', $order->access_token));
        $successPage->assertStatus(200);
        $successPage->assertSee($order->order_code);
        $successPage->assertSee('Buka WhatsApp Admin Vantara');
    }

    public function test_cannot_checkout_if_date_is_already_booked(): void
    {
        $package = Package::first();
        $package->update(['stock' => 1]);
        $eventDate = now()->addDays(7)->format('Y-m-d');

        $order = Order::create([
            'order_code' => 'VAN-20260926-0001',
            'customer_name' => 'Existing Customer',
            'customer_phone' => '08999999999',
            'event_address' => 'Gedung A',
            'event_date' => $eventDate,
            'total_price' => 1000000,
            'status' => 'confirmed',
        ]);
        $order->items()->create([
            'package_id' => $package->id,
            'package_name' => $package->name,
            'unit_price' => $package->price,
            'quantity' => 1,
            'subtotal' => $package->price,
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

    public function test_different_packages_can_be_booked_on_same_date(): void
    {
        $packages = Package::take(2)->get();
        $packageA = $packages->first();
        $packageB = $packages->last();

        $eventDate = now()->addDays(5);
        $dateStr = $eventDate->format('Y-m-d');

        // Create an order for Package B on $dateStr
        $order = Order::create([
            'order_code' => 'VAN-20260926-0002',
            'customer_name' => 'Customer B',
            'customer_phone' => '08999999998',
            'event_address' => 'Gedung B',
            'event_date' => $dateStr,
            'total_price' => 1500000,
            'status' => 'confirmed',
        ]);
        $order->items()->create([
            'package_id' => $packageB->id,
            'package_name' => $packageB->name,
            'unit_price' => $packageB->price,
            'quantity' => 1,
            'subtotal' => $packageB->price,
        ]);

        // 1. Calendar API for Package A should NOT list $dateStr as booked for Package A
        $calendarResponse = $this->getJson("/api/availability/calendar?year={$eventDate->year}&month={$eventDate->month}&package_id={$packageA->id}");
        $calendarResponse->assertStatus(200);
        $bookedDates = collect($calendarResponse->json('booked_dates'));
        $this->assertFalse($bookedDates->contains('date', $dateStr), 'Calendar API for Package A should show date as available when only Package B is booked');

        // 2. Quick check date API for Package A must report available
        $checkResponse = $this->postJson('/api/availability/check', [
            'date' => $dateStr,
            'package_id' => $packageA->id,
        ]);
        $checkResponse->assertStatus(200);
        $checkResponse->assertJson(['available' => true]);

        // 3. User can checkout Package A on $dateStr successfully
        $checkoutResponse = $this->withSession([
            '_token' => 'test-token',
            'cart' => [
                $packageA->id => [
                    'package_id' => $packageA->id,
                    'name' => $packageA->name,
                    'slug' => $packageA->slug,
                    'category' => $packageA->category,
                    'price' => $packageA->price,
                    'quantity' => 1,
                ],
            ],
        ])->post(route('checkout.store'), [
            '_token' => 'test-token',
            'customer_name' => 'Customer A',
            'customer_phone' => '081234567891',
            'event_address' => 'Gedung A',
            'event_date' => $dateStr,
        ]);

        $checkoutResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', ['customer_name' => 'Customer A']);
    }

    public function test_event_date_is_autofilled_on_checkout_page(): void
    {
        $package = Package::first();
        $eventDate = now()->addDays(10)->format('Y-m-d');

        $response = $this->withSession([
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
            'event_date' => $eventDate,
        ])->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertSee('value="'.$eventDate.'"', false);
    }

    public function test_can_process_multiday_checkout_successfully(): void
    {
        $package = Package::first();
        $startDate = now()->addDays(20)->format('Y-m-d');
        $endDate = now()->addDays(21)->format('Y-m-d');

        $response = $this->withSession([
            '_token' => 'test-token',
            'cart' => [
                $package->id => [
                    'package_id' => $package->id,
                    'name' => $package->name,
                    'slug' => $package->slug,
                    'category' => $package->category,
                    'price' => $package->price,
                    'quantity' => 2,
                ],
            ],
        ])->post(route('checkout.store'), [
            '_token' => 'test-token',
            'customer_name' => 'Multi-day Customer',
            'customer_phone' => '081234567899',
            'event_address' => 'Gedung Serbaguna Multi-day',
            'event_date' => $startDate,
            'event_end_date' => $endDate,
        ]);

        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals($startDate, $order->event_date->format('Y-m-d'));
        $this->assertEquals($endDate, $order->event_end_date->format('Y-m-d'));
        // 2 days * package price
        $this->assertEquals($package->price * 2, $order->total_price);
        $response->assertRedirect(route('checkout.show', $order->access_token));
    }

    public function test_same_package_can_be_booked_more_than_once_on_same_date_if_stock_available(): void
    {
        $package = Package::first();
        $package->update(['stock' => 3]);
        $dateStr = now()->addDays(12)->format('Y-m-d');

        // Order 1 (1 unit)
        $order1 = Order::create([
            'order_code' => Order::generateOrderCode(),
            'customer_name' => 'Pemesan Pertama',
            'customer_phone' => '08111111111',
            'event_address' => 'Venue A',
            'event_date' => $dateStr,
            'total_price' => $package->price,
            'status' => 'confirmed',
        ]);
        $order1->items()->create([
            'package_id' => $package->id,
            'package_name' => $package->name,
            'unit_price' => $package->price,
            'quantity' => 1,
            'subtotal' => $package->price,
        ]);

        // Cek API ketersediaan: harus masih TERSEDIA dengan sisa 2 unit
        $checkRes1 = $this->postJson('/api/availability/check', [
            'date' => $dateStr,
            'package_id' => $package->id,
        ]);
        $checkRes1->assertStatus(200);
        $checkRes1->assertJson([
            'available' => true,
            'remaining_stock' => 2,
            'total_stock' => 3,
        ]);

        // Calendar API juga harus menganggap tanggal ini belum penuh (is_fully_booked: false)
        $targetDate = Carbon::parse($dateStr);
        $calRes = $this->getJson("/api/availability/calendar?year={$targetDate->year}&month={$targetDate->month}&package_id={$package->id}");
        $calRes->assertStatus(200);
        $bookedList = collect($calRes->json('booked_dates'));
        $dayEntry = $bookedList->firstWhere('date', $dateStr);
        $this->assertNotNull($dayEntry);
        $this->assertFalse($dayEntry['is_fully_booked']);
        $this->assertEquals(2, $dayEntry['remaining_stock']);

        // Order 2 (1 unit) di tanggal yang sama oleh customer lain harus BERHASIL
        $response2 = $this->withSession([
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
            'customer_name' => 'Pemesan Kedua',
            'customer_phone' => '08222222222',
            'event_address' => 'Venue B',
            'event_date' => $dateStr,
        ]);

        $response2->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', ['customer_name' => 'Pemesan Kedua']);

        // Sekarang sisa stok tinggal 1
        $this->assertEquals(1, $package->getAvailableStockOn($dateStr));
    }

    public function test_can_book_multiple_quantity_in_single_order_within_stock(): void
    {
        $package = Package::first();
        $package->update(['stock' => 3]);
        $dateStr = now()->addDays(16)->format('Y-m-d');

        // Pesan 2 unit sekaligus dalam 1 checkout
        $response = $this->withSession([
            '_token' => 'test-token',
            'cart' => [
                $package->id => [
                    'package_id' => $package->id,
                    'name' => $package->name,
                    'slug' => $package->slug,
                    'category' => $package->category,
                    'price' => $package->price,
                    'quantity' => 2,
                ],
            ],
        ])->post(route('checkout.store'), [
            '_token' => 'test-token',
            'customer_name' => 'Pemesan 2 Unit',
            'customer_phone' => '08333333333',
            'event_address' => 'Venue Akbar',
            'event_date' => $dateStr,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', ['customer_name' => 'Pemesan 2 Unit']);
        $this->assertEquals(1, $package->getAvailableStockOn($dateStr));
    }

    public function test_cannot_book_when_daily_stock_exceeded(): void
    {
        $package = Package::first();
        $package->update(['stock' => 2]);
        $dateStr = now()->addDays(18)->format('Y-m-d');

        // Buat pesanan awal yang menghabiskan seluruh 2 unit stok
        $order = Order::create([
            'order_code' => Order::generateOrderCode(),
            'customer_name' => 'Pemesan Borong',
            'customer_phone' => '08444444444',
            'event_address' => 'Gedung Penuh',
            'event_date' => $dateStr,
            'total_price' => $package->price * 2,
            'status' => 'confirmed',
        ]);
        $order->items()->create([
            'package_id' => $package->id,
            'package_name' => $package->name,
            'unit_price' => $package->price,
            'quantity' => 2,
            'subtotal' => $package->price * 2,
        ]);

        // Percobaan checkout berikutnya harus ditolak
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
            'customer_name' => 'Pemesan Tertolak',
            'customer_phone' => '08555555555',
            'event_address' => 'Gedung Alternatif',
            'event_date' => $dateStr,
        ]);

        $response->assertSessionHasErrors('event_date');
    }
}
