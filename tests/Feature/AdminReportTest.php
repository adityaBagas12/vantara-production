<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportTest extends TestCase
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

        Order::create([
            'order_code' => 'VAN-20260926-0001',
            'customer_name' => 'Pelanggan Laporan',
            'customer_phone' => '081234567890',
            'event_address' => 'Gedung Kartika',
            'event_date' => now()->format('Y-m-d'),
            'total_price' => 3500000,
            'down_payment' => 1500000,
            'status' => 'confirmed',
        ]);
    }

    public function test_admin_can_view_reports_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'month' => now()->month,
            'year' => now()->year,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Bulanan');
        $response->assertSee('VAN-20260926-0001');
    }

    public function test_admin_can_download_report_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.pdf', [
            'month' => now()->month,
            'year' => now()->year,
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_view_print_preview(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.print', [
            'month' => now()->month,
            'year' => now()->year,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Rekapitulasi Penjualan');
    }
}
