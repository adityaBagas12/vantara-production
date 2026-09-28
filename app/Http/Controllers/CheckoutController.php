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
    public function index(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('packages.catalog')
                ->with('error', 'Keranjang Anda masih kosong. Silakan pilih paket layanan terlebih dahulu.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('pages.checkout', compact('cart', 'total'));
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

        // Cek ketersediaan tanggal (mencegah double booking untuk pesanan yang sudah dikonfirmasi)
        $isBooked = Order::whereDate('event_date', $request->event_date)
            ->whereIn('status', ['confirmed', 'dp_received'])
            ->exists();

        if ($isBooked) {
            $formattedDate = Carbon::parse($request->event_date)->translatedFormat('l, d F Y');

            return back()->withInput()->withErrors([
                'event_date' => "Maaf, jadwal pada tanggal {$formattedDate} sudah terisi penuh oleh pemesan lain. Silakan tentukan tanggal alternatif.",
            ]);
        }

        // Kalkulasi Total
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Generate Order Code via model helper
        $orderCode = Order::generateOrderCode();

        // Save order and items in DB Transaction
        $order = DB::transaction(function () use ($validated, $cart, $totalPrice, $orderCode) {
            $order = Order::create([
                'order_code' => $orderCode,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'event_address' => $validated['event_address'],
                'event_date' => $validated['event_date'],
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
        $eventDateFormatted = Carbon::parse($order->event_date)->translatedFormat('l, d F Y');

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
