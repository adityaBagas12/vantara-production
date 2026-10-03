<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama portal admin.
     */
    public function index(): View
    {
        // 1. Ringkasan Metrik
        $totalOrdersCount = Order::count();

        // Total pendapatan dari transaksi yang sah (DP Received, Confirmed, Completed)
        $totalRevenue = Order::whereIn('status', ['dp_received', 'confirmed', 'completed'])
            ->sum('total_price');

        // Total DP yang sudah diterima
        $totalDpCollected = Order::whereIn('status', ['dp_received', 'confirmed', 'completed'])
            ->sum('down_payment');

        // Pesanan Butuh Tindakan (Pending)
        $pendingCount = Order::where('status', 'pending')->count();

        // Pesanan Dikonfirmasi & DP Diterima
        $confirmedCount = Order::whereIn('status', ['dp_received', 'confirmed'])->count();

        // Pesanan Selesai
        $completedCount = Order::where('status', 'completed')->count();

        // Total Paket Aktif
        $activePackagesCount = Package::where('is_active', true)->count();

        // 2. Transaksi Terkini (5 Terbaru)
        $recentOrders = Order::with('orderItems')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        // 3. Rekap Performa Penjualan Bulanan (Tahun Berjalan)
        $monthlyPerformance = Order::select(
            DB::raw('MONTH(event_date) as month_num'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(CASE WHEN status IN ("dp_received", "confirmed", "completed") THEN total_price ELSE 0 END) as revenue'),
            DB::raw('SUM(CASE WHEN status IN ("dp_received", "confirmed", "completed") THEN down_payment ELSE 0 END) as dp_collected')
        )
            ->whereYear('event_date', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(event_date)'))
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');

        // Format data 12 bulan untuk statistik
        $monthlyChartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthName = Carbon::create()->month($m)->translatedFormat('F');
            $data = $monthlyPerformance->get($m);
            $monthlyChartData[] = [
                'month_num' => $m,
                'month' => $monthName,
                'orders' => $data ? $data->total_orders : 0,
                'revenue' => $data ? (int) $data->revenue : 0,
                'dp_collected' => $data ? (int) $data->dp_collected : 0,
            ];
        }

        return view('admin.dashboard', compact(
            'totalOrdersCount',
            'totalRevenue',
            'totalDpCollected',
            'pendingCount',
            'confirmedCount',
            'completedCount',
            'activePackagesCount',
            'recentOrders',
            'monthlyChartData'
        ));
    }
}
