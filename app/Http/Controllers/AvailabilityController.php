<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvailabilityController extends Controller
{
    /**
     * Display the dedicated availability checking page.
     */
    public function index(Request $request): View
    {
        if ($request->filled('event_date')) {
            session()->put('event_date', $request->input('event_date'));
        } elseif ($request->filled('date')) {
            session()->put('event_date', $request->input('date'));
        }

        $packages = Package::active()->orderBy('name', 'asc')->get();
        $selectedPackageId = $request->integer('package_id');

        return view('pages.availability', [
            'packages' => $packages,
            'selectedPackageId' => $selectedPackageId,
        ]);
    }

    /**
     * Return calendar booked dates for a given month and year.
     */
    public function calendarData(Request $request): JsonResponse
    {
        $request->validate([
            'year' => ['required', 'integer', 'between:2025,2035'],
            'month' => ['required', 'integer', 'between:1,12'],
            'package_id' => ['nullable', 'integer', 'exists:packages,id'],
        ]);

        $year = $request->integer('year');
        $month = $request->integer('month');
        $packageId = $request->input('package_id');

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $query = Order::booked()
            ->whereDate('event_date', '<=', $endOfMonth->format('Y-m-d'))
            ->whereDate(DB::raw('COALESCE(event_end_date, event_date)'), '>=', $startOfMonth->format('Y-m-d'));

        if ($packageId) {
            $query->whereHas('items', function ($itemQuery) use ($packageId) {
                $itemQuery->where('package_id', $packageId);
            });
        }

        $orders = $query->with('items.package')->get();

        $bookedDates = collect();
        foreach ($orders as $order) {
            $packageName = $order->items->first()?->package_name ?? 'Layanan Vantara Production';
            $start = Carbon::parse($order->event_date)->startOfDay();
            $end = $order->event_end_date ? Carbon::parse($order->event_end_date)->startOfDay() : $start->copy();

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                if ($date->month === $month && $date->year === $year) {
                    $bookedDates->push([
                        'date' => $date->format('Y-m-d'),
                        'status' => $order->status,
                        'status_label' => match ($order->status) {
                            'confirmed' => 'Terkonfirmasi (Booked)',
                            'dp_received' => 'DP Terbayar (Booked)',
                            'pending' => 'Proses Verifikasi',
                            default => 'Booked',
                        },
                        'package_name' => $packageName,
                    ]);
                }
            }
        }

        return response()->json([
            'year' => $year,
            'month' => $month,
            'booked_dates' => $bookedDates->values(),
        ]);
    }

    /**
     * Quick check if a specific date is available.
     */
    public function checkDate(Request $request): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'package_id' => ['nullable', 'integer', 'exists:packages,id'],
        ]);

        $dateString = $request->string('date')->toString();
        $targetDate = Carbon::parse($dateString)->startOfDay();
        $today = Carbon::today();

        if ($targetDate->lt($today)) {
            return response()->json([
                'available' => false,
                'is_past' => true,
                'date' => $dateString,
                'formatted_date' => $targetDate->translatedFormat('l, d F Y'),
                'message' => 'Tanggal ini sudah berlalu. Silakan pilih tanggal yang akan datang.',
            ]);
        }

        $query = Order::booked()
            ->whereDate('event_date', '<=', $dateString)
            ->whereDate(DB::raw('COALESCE(event_end_date, event_date)'), '>=', $dateString);

        if ($request->filled('package_id')) {
            $packageId = $request->integer('package_id');
            $query->whereHas('items', function ($itemQuery) use ($packageId) {
                $itemQuery->where('package_id', $packageId);
            });
        }

        $existingOrder = $query->with('items.package')->first();

        if ($existingOrder) {
            $packageName = $existingOrder->items->first()?->package_name ?? 'Layanan';

            return response()->json([
                'available' => false,
                'is_past' => false,
                'date' => $dateString,
                'formatted_date' => $targetDate->translatedFormat('l, d F Y'),
                'message' => "Jadwal pada tanggal ini telah terisi ({$packageName}). Silakan pilih tanggal alternatif lain atau hubungi tim konsultan kami via WhatsApp.",
            ]);
        }

        session()->put('event_date', $dateString);

        return response()->json([
            'available' => true,
            'is_past' => false,
            'date' => $dateString,
            'formatted_date' => $targetDate->translatedFormat('l, d F Y'),
            'message' => 'Selamat! Jadwal pada tanggal ini masih TERSEDIA. Anda dapat melanjutkan ke pemesanan atau konsultasi sekarang.',
        ]);
    }
}
