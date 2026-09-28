<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AdminReportController extends Controller
{
    /**
     * Tampilkan halaman analisis & laporan rekapitulasi penjualan.
     */
    public function index(Request $request): View
    {
        $queryData = $this->getReportData($request);

        return view('admin.reports.index', array_merge($queryData, [
            'startDate' => $queryData['startDate'],
            'endDate' => $queryData['endDate'],
            'month' => $queryData['month'],
            'year' => $queryData['year'],
        ]));
    }

    /**
     * Unduh atau tampilkan laporan rekapitulasi dalam format PDF.
     */
    public function downloadPdf(Request $request): Response
    {
        $data = $this->getReportData($request);

        $pdf = Pdf::loadView('admin.reports.pdf', $data)
            ->setPaper('a4', 'portrait');

        $fileName = 'Laporan_Vantara_Production_'.sprintf('%02d', $data['month']).'_'.$data['year'].'.pdf';
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $fileName = 'Laporan_Vantara_Production_'.$request->start_date.'_to_'.$request->end_date.'.pdf';
        }

        if ($request->boolean('stream')) {
            return $pdf->stream($fileName);
        }

        return $pdf->download($fileName);
    }

    /**
     * Tampilkan halaman cetak ramah printer.
     */
    public function printView(Request $request): View
    {
        $data = $this->getReportData($request);

        return view('admin.reports.pdf', array_merge($data, ['isPrint' => true]));
    }

    /**
     * Helper untuk menghitung metrik & query laporan berdasarkan filter.
     */
    private function getReportData(Request $request): array
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $periodLabel = $startDate->translatedFormat('d M Y').' — '.$endDate->translatedFormat('d M Y');
        } else {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->startOfDay();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
            $periodLabel = $startDate->translatedFormat('F Y');
        }

        // Query transaksi dalam rentang tanggal
        $ordersQuery = Order::with('orderItems.package')
            ->whereBetween('event_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        $orders = (clone $ordersQuery)->orderBy('event_date', 'asc')->get();

        // Metrik Finansial
        $totalOrdersCount = $orders->count();

        $approvedOrders = $orders->filter(fn ($o) => in_array($o->status, ['dp_received', 'confirmed', 'completed']));

        $totalRevenue = $approvedOrders->sum('total_price');
        $totalDp = $approvedOrders->sum('down_payment');
        $totalRemaining = max(0, $totalRevenue - $totalDp);

        $pendingCount = $orders->where('status', 'pending')->count();
        $confirmedCount = $orders->whereIn('status', ['dp_received', 'confirmed'])->count();
        $completedCount = $orders->where('status', 'completed')->count();
        $cancelledCount = $orders->where('status', 'cancelled')->count();

        // Breakdown per Kategori Paket
        $categoryBreakdown = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('packages', 'order_items.package_id', '=', 'packages.id')
            ->whereBetween('orders.event_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->whereIn('orders.status', ['dp_received', 'confirmed', 'completed'])
            ->select('packages.category', DB::raw('COUNT(*) as total_items'), DB::raw('SUM(order_items.subtotal) as total_amount'))
            ->groupBy('packages.category')
            ->get();

        // Riwayat Rekap 12 Bulan Terakhir
        $historyMonthly = Order::select(
            DB::raw('YEAR(event_date) as year_val'),
            DB::raw('MONTH(event_date) as month_val'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(CASE WHEN status IN ("dp_received", "confirmed", "completed") THEN total_price ELSE 0 END) as revenue'),
            DB::raw('SUM(CASE WHEN status IN ("dp_received", "confirmed", "completed") THEN down_payment ELSE 0 END) as dp_collected')
        )
            ->groupBy(DB::raw('YEAR(event_date)'), DB::raw('MONTH(event_date)'))
            ->orderBy('year_val', 'desc')
            ->orderBy('month_val', 'desc')
            ->take(12)
            ->get();

        return [
            'orders' => $orders,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'month' => (int) $month,
            'year' => (int) $year,
            'periodLabel' => $periodLabel,
            'totalOrdersCount' => $totalOrdersCount,
            'totalRevenue' => $totalRevenue,
            'totalDp' => $totalDp,
            'totalRemaining' => $totalRemaining,
            'pendingCount' => $pendingCount,
            'confirmedCount' => $confirmedCount,
            'completedCount' => $completedCount,
            'cancelledCount' => $cancelledCount,
            'categoryBreakdown' => $categoryBreakdown,
            'historyMonthly' => $historyMonthly,
        ];
    }
}
