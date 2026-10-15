<?php

namespace Tests\Feature;

use App\Exceptions\StockUnavailableException;
use App\Models\Order;
use App\Models\Package;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BookingReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_second_checkout_for_stock_one_is_rejected(): void
    {
        $package = Package::factory()->create(['stock' => 1]);
        $service = app(BookingService::class);
        $cart = $this->cart($package, 1);

        $service->reserve($this->customer('Pertama'), $cart, $package->price, $this->date('2026-11-10'), $this->date('2026-11-10'));

        $this->expectException(StockUnavailableException::class);
        try {
            $service->reserve($this->customer('Kedua'), $cart, $package->price, $this->date('2026-11-10'), $this->date('2026-11-10'));
        } finally {
            $this->assertSame(1, Order::count());
        }
    }

    public function test_partial_full_multi_day_range_rolls_back_completely(): void
    {
        $package = Package::factory()->create(['stock' => 1]);
        $existing = Order::factory()->create([
            'event_date' => '2026-11-11',
            'event_end_date' => '2026-11-11',
            'status' => 'confirmed',
        ]);
        $existing->items()->create([
            'package_id' => $package->id,
            'package_name' => $package->name,
            'unit_price' => $package->price,
            'quantity' => 1,
            'subtotal' => $package->price,
        ]);

        $before = Order::count();
        try {
            app(BookingService::class)->reserve($this->customer('Multi Hari'), $this->cart($package), $package->price * 2, $this->date('2026-11-10'), $this->date('2026-11-11'));
            $this->fail('Expected stock rejection.');
        } catch (StockUnavailableException) {
            $this->assertSame($before, Order::count());
            $this->assertSame(0, DB::table('order_items')->whereNotIn('order_id', Order::query()->pluck('id'))->count());
        }
    }

    public function test_quantity_must_fit_remaining_stock(): void
    {
        $package = Package::factory()->create(['stock' => 3]);
        $existing = Order::factory()->create(['event_date' => '2026-11-12', 'status' => 'confirmed']);
        $existing->items()->create([
            'package_id' => $package->id,
            'package_name' => $package->name,
            'unit_price' => $package->price,
            'quantity' => 1,
            'subtotal' => $package->price,
        ]);

        $service = app(BookingService::class);
        try {
            $service->reserve($this->customer('Terlalu Banyak'), $this->cart($package, 3), $package->price * 3, $this->date('2026-11-12'), $this->date('2026-11-12'));
            $this->fail('Expected stock rejection.');
        } catch (StockUnavailableException) {
            $this->assertSame(2, $package->fresh()->getAvailableStockOn('2026-11-12'));
            $service->reserve($this->customer('Pas'), $this->cart($package, 2), $package->price * 2, $this->date('2026-11-12'), $this->date('2026-11-12'));
            $this->assertSame(2, Order::count());
        }
    }

    public function test_reservation_rechecks_inside_transaction_and_locks_packages(): void
    {
        $package = Package::factory()->create(['stock' => 1]);
        $queries = [];
        DB::listen(function ($query) use (&$queries): void { $queries[] = strtolower($query->sql); });

        $service = app(BookingService::class);
        $service->reserve($this->customer('Pertama'), $this->cart($package), $package->price, $this->date('2026-11-13'), $this->date('2026-11-13'));

        $this->expectException(StockUnavailableException::class);
        try {
            $service->reserve($this->customer('Kedua'), $this->cart($package), $package->price, $this->date('2026-11-13'), $this->date('2026-11-13'));
        } finally {
            $this->assertTrue(collect($queries)->contains(fn (string $sql): bool => str_contains($sql, 'for update') && str_contains($sql, 'packages')));
        }
    }

    public function test_order_code_conflict_retries_without_duplicate_order(): void
    {
        $existing = Order::factory()->create(['order_code' => 'VAN-20261114-0001']);
        $package = Package::factory()->create();
        $attempts = 0;
        $service = new class($attempts) extends BookingService {
            private int $attempts;

            public function __construct(int &$attempts)
            {
                $this->attempts =& $attempts;
            }

            protected function nextOrderCode(): string
            {
                $this->attempts++;
                return $this->attempts === 1 ? 'VAN-20261114-0001' : 'VAN-20261114-0002';
            }
        };

        $order = $service->reserve($this->customer('Retry'), $this->cart($package), $package->price, $this->date('2026-11-14'), $this->date('2026-11-14'));
        $this->assertSame('VAN-20261114-0002', $order->order_code);
        $this->assertSame(2, Order::count());
    }

    public function test_different_packages_can_be_reserved_on_the_same_date(): void
    {
        $first = Package::factory()->create(['stock' => 1]);
        $second = Package::factory()->create(['stock' => 1]);
        $service = app(BookingService::class);

        $service->reserve($this->customer('Paket A'), $this->cart($first), $first->price, $this->date('2026-11-15'), $this->date('2026-11-15'));
        $service->reserve($this->customer('Paket B'), $this->cart($second), $second->price, $this->date('2026-11-15'), $this->date('2026-11-15'));

        $this->assertSame(2, Order::count());
    }

    private function cart(Package $package, int $quantity = 1): array
    {
        return [$package->id => [
            'package_id' => $package->id,
            'name' => $package->name,
            'price' => $package->price,
            'quantity' => $quantity,
        ]];
    }

    private function customer(string $name): array
    {
        return [
            'customer_name' => $name,
            'customer_phone' => '081234567890',
            'customer_email' => null,
            'event_address' => 'Alamat Testing',
            'notes' => null,
        ];
    }

    private function date(string $date): Carbon
    {
        return Carbon::parse($date)->startOfDay();
    }
}
