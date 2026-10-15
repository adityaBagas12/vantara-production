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

        if ($packageId) {
            $package = Package::find($packageId);
            $totalStock = $package ? (int) $package->stock : 1;
            $packageName = $package?->name ?? 'Layanan Vantara Production';

            // Hitung kuantitas terpakai per tanggal
            $dailyBookedQty = [];
            foreach ($orders as $order) {
                $itemsForPkg = $order->items->where('package_id', $packageId);
                $orderQty = $itemsForPkg->sum('quantity');

                $start = Carbon::parse($order->event_date)->startOfDay();
                $end = $order->event_end_date ? Carbon::parse($order->event_end_date)->startOfDay() : $start->copy();

                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                    if ($date->month === $month && $date->year === $year) {
                        $dStr = $date->format('Y-m-d');
                        $dailyBookedQty[$dStr] = ($dailyBookedQty[$dStr] ?? 0) + $orderQty;
                    }
                }
            }

            foreach ($dailyBookedQty as $dStr => $bookedQty) {
                $remainingStock = max(0, $totalStock - $bookedQty);
                $isFullyBooked = ($remainingStock <= 0);

                $bookedDates->push([
                    'date' => $dStr,
                    'is_fully_booked' => $isFullyBooked,
                    'is_booked' => $isFullyBooked,
                    'remaining_stock' => $remainingStock,
                    'total_stock' => $totalStock,
                    'booked_quantity' => $bookedQty,
                    'status' => $isFullyBooked ? 'confirmed' : 'partially_booked',
                    'status_label' => $isFullyBooked ? 'Sudah Terisi Penuh (Booked)' : "Masih Tersedia (Sisa {$remainingStock} Unit)",
                    'package_name' => $packageName,
                ]);
            }
        } else {
            // Jika filter semua paket dipilih
            $activePackages = Package::active()->get();
            $dailyPackageBookings = [];

            foreach ($orders as $order) {
                $start = Carbon::parse($order->event_date)->startOfDay();
                $end = $order->event_end_date ? Carbon::parse($order->event_end_date)->startOfDay() : $start->copy();

                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                    if ($date->month === $month && $date->year === $year) {
                        $dStr = $date->format('Y-m-d');
                        foreach ($order->items as $item) {
                            if ($item->package_id) {
                                $dailyPackageBookings[$dStr][$item->package_id] = ($dailyPackageBookings[$dStr][$item->package_id] ?? 0) + $item->quantity;
                            }
                        }
                    }
                }
            }

            foreach ($dailyPackageBookings as $dStr => $pkgBookings) {
                $allFull = true;
                foreach ($activePackages as $actPkg) {
                    $used = $pkgBookings[$actPkg->id] ?? 0;
                    if ($used < $actPkg->stock) {
                        $allFull = false;
                        break;
                    }
                }

                $bookedDates->push([
                    'date' => $dStr,
                    'is_fully_booked' => $allFull,
                    'is_booked' => $allFull,
                    'status' => $allFull ? 'confirmed' : 'partially_booked',
                    'status_label' => $allFull ? 'Semua Paket Terisi Penuh' : 'Ada Pesanan (Sebagian Masih Tersedia)',
                    'package_name' => 'Layanan Vantara Production',
                ]);
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
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $dateString = $request->string('date')->toString();
        $targetDate = Carbon::parse($dateString)->startOfDay();
        $today = Carbon::today();
        $requestedQty = max(1, $request->integer('quantity', 1));

        if ($targetDate->lt($today)) {
            return response()->json([
                'available' => false,
                'is_past' => true,
                'date' => $dateString,
                'formatted_date' => $targetDate->translatedFormat('l, d F Y'),
                'message' => 'Tanggal ini sudah berlalu. Silakan pilih tanggal yang akan datang.',
            ]);
        }

        if ($request->filled('package_id')) {
            $package = Package::find($request->integer('package_id'));

            if (! $package) {
                return response()->json([
                    'available' => false,
                    'message' => 'Paket tidak ditemukan.',
                ], 404);
            }

            $totalStock = (int) $package->stock;
            $bookedQuantity = $package->getBookedStockOn($targetDate);
            $remaining = max(0, $totalStock - $bookedQuantity);

            if ($remaining < $requestedQty) {
                $statusMsg = $remaining === 0
                    ? "Jadwal pada tanggal ini telah terisi penuh untuk paket {$package->name} ({$totalStock} unit terpakai). Silakan pilih tanggal alternatif lain atau hubungi kami via WhatsApp."
                    : "Kapasitas tidak mencukupi. Paket {$package->name} pada tanggal ini hanya tersisa {$remaining} unit (Anda meminta {$requestedQty} unit).";

                return response()->json([
                    'available' => false,
                    'is_past' => false,
                    'date' => $dateString,
                    'formatted_date' => $targetDate->translatedFormat('l, d F Y'),
                    'remaining_stock' => $remaining,
                    'total_stock' => $totalStock,
                    'booked_quantity' => $bookedQuantity,
                    'message' => $statusMsg,
                ]);
            }

            session()->put('event_date', $dateString);

            $availMsg = $bookedQuantity > 0
                ? "Selamat! Jadwal pada tanggal ini masih TERSEDIA. Tersisa {$remaining} dari {$totalStock} unit yang dapat dipesan."
                : "Selamat! Jadwal pada tanggal ini masih TERSEDIA ({$totalStock} unit tersedia). Anda dapat melanjutkan ke pemesanan sekarang.";

            return response()->json([
                'available' => true,
                'is_past' => false,
                'date' => $dateString,
                'formatted_date' => $targetDate->translatedFormat('l, d F Y'),
                'remaining_stock' => $remaining,
                'total_stock' => $totalStock,
                'booked_quantity' => $bookedQuantity,
                'message' => $availMsg,
            ]);
        }

        // Jika tidak ada package_id yang ditentukan
        $activePackages = Package::active()->get();
        $allFull = true;
        foreach ($activePackages as $actPkg) {
            if ($actPkg->getAvailableStockOn($targetDate) >= $requestedQty) {
                $allFull = false;
                break;
            }
        }

        if ($allFull && $activePackages->isNotEmpty()) {
            return response()->json([
                'available' => false,
                'is_past' => false,
                'date' => $dateString,
                'formatted_date' => $targetDate->translatedFormat('l, d F Y'),
                'message' => 'Seluruh paket layanan pada tanggal ini telah terisi penuh. Silakan pilih tanggal alternatif lain.',
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
