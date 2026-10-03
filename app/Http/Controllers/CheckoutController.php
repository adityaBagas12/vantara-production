<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Tampilkan formulir checkout & isi data diri pemesan.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->filled('event_date')) {
            session()->put('event_date', $request->input('event_date'));
        } elseif ($request->filled('date')) {
            session()->put('event_date', $request->input('date'));
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('packages.catalog')
                ->with('error', 'Keranjang Anda masih kosong. Silakan pilih paket layanan terlebih dahulu.');
        }

        $total = 0;
        $rentDuration = 1;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $rentDuration = max($rentDuration, (int) ($item['quantity'] ?? 1));
        }

        return view('pages.checkout', compact('cart', 'total', 'rentDuration'));
    }

    /**
     * Proses pengiriman pemesanan (Submit Order & Save to DB).
     */
    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('packages.catalog')
                ->with('error', 'Keranjang Anda masih kosong. Silakan pilih paket layanan terlebih dahulu.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:255',
            'event_address' => 'required|string|max:1000',
            'event_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'Silakan masukkan nama lengkap Anda.',
            'customer_phone.required' => 'Silakan masukkan nomor WhatsApp aktif Anda.',
            'event_address.required' => 'Silakan masukkan alamat lengkap tempat acara.',
            'event_date.required' => 'Silakan pilih tanggal pelaksanaan acara.',
            'event_date.after_or_equal' => 'Tanggal acara tidak boleh di masa lalu.',
        ]);

        $rentDuration = 1;
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
            $rentDuration = max($rentDuration, (int) ($item['quantity'] ?? 1));
        }

        $startDate = Carbon::parse($validated['event_date'])->startOfDay();
        $endDate = $startDate->copy()->addDays($rentDuration - 1);

        $cartPackageIds = array_filter(array_column($cart, 'package_id'));

        // Cek ketersediaan tanggal per paket layanan dalam keranjang (mencegah double booking untuk paket yang sama)
        $isBooked = Order::booked()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereDate('event_date', '<=', $endDate->format('Y-m-d'))
                    ->whereDate(DB::raw('COALESCE(event_end_date, event_date)'), '>=', $startDate->format('Y-m-d'));
            })
            ->whereHas('items', function ($itemQuery) use ($cartPackageIds) {
                $itemQuery->whereIn('package_id', $cartPackageIds);
            })
            ->exists();

        if ($isBooked) {
            $formattedDate = $startDate->translatedFormat('d M Y');
            if ($rentDuration > 1) {
                $formattedDate .= ' s/d '.$endDate->translatedFormat('d M Y');
            }

            return back()->withInput()->withErrors([
                'event_date' => "Maaf, paket layanan yang Anda pilih pada tanggal {$formattedDate} sudah terisi oleh pemesan lain. Silakan tentukan tanggal alternatif.",
            ]);
        }

        // Generate Order Code via model helper
        $orderCode = Order::generateOrderCode();

        // Save order and items in DB Transaction
        $order = DB::transaction(function () use ($validated, $cart, $totalPrice, $orderCode, $startDate, $endDate, $rentDuration) {
            $order = Order::create([
                'order_code' => $orderCode,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'event_address' => $validated['event_address'],
                'event_date' => $startDate->format('Y-m-d'),
                'event_end_date' => $rentDuration > 1 ? $endDate->format('Y-m-d') : $startDate->format('Y-m-d'),
                'notes' => $validated['notes'] ?? null,
                'total_price' => $totalPrice,
                'down_payment' => 0,
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'package_id' => $item['package_id'],
                    'package_name' => $item['name'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'options_json' => isset($item['options']) ? json_encode($item['options']) : null,
                ]);
            }

            return $order;
        });

        // Clear cart session
        session()->forget('cart');

        return redirect()->route('checkout.show', $order->order_code)
            ->with('success', 'Pemesanan berhasil dibuat! Silakan hubungi Admin via WhatsApp untuk konfirmasi dan negosiasi.');
    }

    /**
     * Tampilkan konfirmasi & ringkasan pesanan berhasil.
     */
    public function show(string $orderCode): View
    {
        $order = Order::with('orderItems.package')->where('order_code', $orderCode)->firstOrFail();

        // Template pesan WhatsApp Admin
        $waNumber = '6282282422317';
        $startDate = Carbon::parse($order->event_date);
        if ($order->event_end_date && $order->event_end_date != $order->event_date) {
            $endDate = Carbon::parse($order->event_end_date);
            $days = $startDate->diffInDays($endDate) + 1;
            $eventDateFormatted = $startDate->translatedFormat('d M Y').' s/d '.$endDate->translatedFormat('d M Y')." ({$days} Hari)";
        } else {
            $eventDateFormatted = $startDate->translatedFormat('l, d F Y');
        }

        $waText = "Halo Vantara Production, saya telah membuat pesanan melalui website dengan rincian berikut:\n\n";
        $waText .= "📋 *KODE PESANAN:* {$order->order_code}\n";
        $waText .= "👤 *Nama Pemesan:* {$order->customer_name}\n";
        $waText .= "📱 *No. WhatsApp:* {$order->customer_phone}\n";
        $waText .= "📅 *Tanggal Acara:* {$eventDateFormatted}\n";
        $waText .= "📍 *Alamat Acara:* {$order->event_address}\n";
        if ($order->notes) {
            $waText .= "📝 *Catatan Khusus:* {$order->notes}\n";
        }
        $waText .= "\n📦 *PAKET YANG DIPESAN:*\n";

        foreach ($order->orderItems as $item) {
            $subtotalFormatted = number_format($item->subtotal, 0, ',', '.');
            $waText .= "• {$item->package_name} ({$item->quantity}x) — Rp {$subtotalFormatted}\n";
        }

        $waText .= "\n💰 *TOTAL ESTIMASI:* Rp ".number_format($order->total_price, 0, ',', '.')."\n\n";
        $waText .= 'Mohon bantu cek ketersediaan jadwal serta info proses pembayaran Uang Muka (DP). Terima kasih!';

        $waUrl = 'https://wa.me/'.$waNumber.'?text='.urlencode($waText);

        return view('pages.order_success', compact('order', 'waUrl'));
    }
}
