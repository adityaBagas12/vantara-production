<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_packages_can_be_created_and_queried_by_scopes(): void
    {
        $activePackage = Package::factory()->create([
            'name' => 'Live Streaming Pro',
            'is_active' => true,
            'is_featured' => true,
        ]);

        $inactivePackage = Package::factory()->create([
            'name' => 'Old Package',
            'is_active' => false,
            'is_featured' => false,
        ]);

        $this->assertCount(1, Package::active()->get());
        $this->assertCount(1, Package::featured()->get());
        $this->assertEquals('Live Streaming Pro', Package::active()->first()->name);
    }

    public function test_orders_can_be_created_with_items_and_relationships(): void
    {
        $package = Package::factory()->create(['name' => 'Dokumentasi Video']);

        $order = Order::factory()->create([
            'order_code' => Order::generateOrderCode(),
            'customer_name' => 'Budi',
            'status' => 'confirmed',
        ]);

        $item = OrderItem::factory()->create([
            'order_id' => $order->id,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'unit_price' => $package->price,
            'quantity' => 1,
            'subtotal' => $package->price,
        ]);

        $this->assertCount(1, $order->items);
        $this->assertEquals('Dokumentasi Video', $order->items->first()->package_name);
        $this->assertEquals($order->id, $item->order->id);
        $this->assertEquals($package->id, $item->package->id);
    }

    public function test_order_code_generation_is_unique_and_incremental(): void
    {
        $code1 = Order::generateOrderCode();
        $order1 = Order::factory()->create(['order_code' => $code1]);

        // Soft delete order1
        $order1->delete();

        $code2 = Order::generateOrderCode();
        $this->assertNotEquals($code1, $code2);
        $this->assertStringStartsWith('VAN-', $code2);
    }

    public function test_admin_user_creation(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@vantara.id',
            'is_admin' => true,
        ]);

        $this->assertTrue($admin->is_admin);
        $this->assertDatabaseHas('users', ['email' => 'admin@vantara.id']);
    }
}
