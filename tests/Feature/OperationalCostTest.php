<?php

namespace Tests\Feature;

use App\Models\OperationalCost;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperationalCostTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_operational_costs_for_an_order(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $order = Order::factory()->create([
            'status' => 'confirmed',
            'total_price' => 2350000,
            'event_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.operational-costs.store', $order->id), [
            'name' => 'Transportasi',
            'amount' => 350000,
        ]);

        $cost = OperationalCost::first();
        $response->assertRedirect(route('admin.orders.show', $order->id));
        $this->assertDatabaseHas('operational_costs', [
            'order_id' => $order->id,
            'name' => 'Transportasi',
            'amount' => 350000,
        ]);

        $this->actingAs($admin)->patch(route('admin.orders.operational-costs.update', [$order->id, $cost->id]), [
            'name' => 'Bensin',
            'amount' => 400000,
        ]);

        $this->assertDatabaseHas('operational_costs', [
            'id' => $cost->id,
            'name' => 'Bensin',
            'amount' => 400000,
        ]);

        $this->actingAs($admin)->delete(route('admin.orders.operational-costs.destroy', [$order->id, $cost->id]));

        $this->assertDatabaseMissing('operational_costs', ['id' => $cost->id]);
    }

    public function test_report_includes_net_revenue_after_operational_costs(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $order = Order::factory()->create([
            'status' => 'completed',
            'total_price' => 2350000,
            'event_date' => now()->format('Y-m-d'),
        ]);
        $order->operationalCosts()->create(['name' => 'Transportasi', 'amount' => 350000]);

        $response = $this->actingAs($admin)->get(route('admin.reports.index', [
            'month' => now()->month,
            'year' => now()->year,
        ]));

        $response->assertOk();
        $response->assertSee('Pendapatan bersih');
        $response->assertSee('Rp 2.000.000');
    }
}
