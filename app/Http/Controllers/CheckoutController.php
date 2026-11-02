<?php

namespace App\Http\Controllers;

use App\Exceptions\OrderCodeConflictException;
use App\Exceptions\StockUnavailableException;
use App\Models\Order;
use App\Models\Package;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private readonly BookingService $bookingService)
    {
    }

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
        if ($request->filled('event_end_date')) {
            session()->put('event_end_date', $request->input('event_end_date'));
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('packages.catalog')
                ->with('error', 'Keranjang Anda masih kosong. Silakan pilih paket layanan terlebih dahulu.');
        }

        $total = 0;
        $rentDuration = $this->rentDuration();
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'] * $rentDuration;
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
            'customer_phone' => ['required', 'regex:/^[0-9]+$/', 'max:20'],
            'customer_email' => 'nullable|email|max:255',
            'event_address' => 'required|string|max:1000',
            'event_date' => 'required|date|after_or_equal:today',
            'event_end_date' => 'nullable|date|after_or_equal:event_date',
            'notes' => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'Silakan masukkan nama lengkap Anda.',
            'customer_phone.required' => 'Silakan masukkan nomor WhatsApp aktif Anda.',
            'customer_phone.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'event_address.required' => 'Silakan masukkan alamat lengkap tempat acara.',
            'event_date.required' => 'Silakan pilih tanggal pelaksanaan acara.',
            'event_date.after_or_equal' => 'Tanggal acara tidak boleh di masa lalu.',
            'event_end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $startDate = Carbon::parse($validated['event_date'])->startOfDay();
        $endDate = $request->filled('event_end_date')
            ? Carbon::parse($request->input('event_end_date'))->startOfDay()
            : $startDate->copy();

        if ($endDate->lt($startDate)) {
            $endDate = $startDate->copy();
        }

        $rentDuration = $startDate->diffInDays($endDate) + 1;
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'] * $rentDuration;
        }

        // Cek kapasitas stok per paket layanan dalam keranjang untuk setiap hari pelaksanaan acara
        foreach ($cart as $item) {
            $package = Package::find($item['package_id']);
            $stock = $package ? (int) $package->stock : 1;
            $requestedQty = (int) ($item['quantity'] ?? 1);

            for ($curr = $startDate->copy(); $curr->lte($endDate); $curr->addDay()) {
                $bookedOnDay = $package ? $package->getBookedStockOn($curr) : 0;
                $remainingOnDay = max(0, $stock - $bookedOnDay);

                if ($requestedQty > $remainingOnDay) {
                    $formattedDay = $curr->translatedFormat('d M Y');
                    if ($remainingOnDay === 0) {
                        $errorMsg = "Maaf, paket '{$item['name']}' pada tanggal {$formattedDay} sudah terisi penuh (kapasitas {$stock} unit telah terpakai). Silakan tentukan tanggal alternatif.";
                    } else {
                        $errorMsg = "Maaf, paket '{$item['name']}' pada tanggal {$formattedDay} hanya tersisa {$remainingOnDay} unit (Anda meminta {$requestedQty} unit). Silakan sesuaikan jumlah unit atau pilih tanggal lain.";
                    }

                    return back()->withInput()->withErrors([
                        'event_date' => $errorMsg,
                    ]);
                }
            }
        }

        try {
            $order = $this->bookingService->reserve($validated, $cart, $totalPrice, $startDate, $endDate);
        } catch (StockUnavailableException|OrderCodeConflictException $exception) {
            return back()->withInput()->withErrors(['event_date' => $exception->getMessage()]);
        }

        // Clear cart session
        session()->forget('cart');

        return redirect()->route('checkout.success', $order->access_token)
            ->with('success', 'Pemesanan berhasil dibuat! Silakan hubungi Admin via WhatsApp untuk konfirmasi dan negosiasi.');
    }

    /**
     * Tampilkan konfirmasi & ringkasan pesanan berhasil.
     */
    public function show(string $accessToken): View
    {
        $order = Order::with('orderItems.package')->where('access_token', $accessToken)->firstOrFail();

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
            $subtotalFormatted = number_format($order->negotiated_subtotals[$item->id] ?? $item->subtotal, 0, ',', '.');
            $waText .= "• {$item->package_name} ({$item->quantity}x) — Rp {$subtotalFormatted}\n";
        }

        $waText .= "\n💰 *TOTAL ESTIMASI:* Rp ".number_format($order->total_price, 0, ',', '.')."\n\n";
        $waText .= '🔗 *INVOICE:* '.route('checkout.show', $order->access_token)."\n\n";
        $waText .= 'Mohon bantu cek ketersediaan jadwal serta info proses pembayaran Uang Muka (DP). Terima kasih!';

        $waUrl = 'https://wa.me/'.$waNumber.'?text='.urlencode($waText);

        $showInvoice = request()->routeIs('checkout.show');

        return view('pages.order_success', compact('order', 'waUrl', 'showInvoice'));
    }

    private function rentDuration(): int
    {
        $start = session('event_date');
        $end = session('event_end_date');
        if (! $start || ! $end) {
            return 1;
        }

        $startDate = Carbon::parse($start)->startOfDay();
        $endDate = Carbon::parse($end)->startOfDay();

        return $endDate->lt($startDate) ? 1 : $startDate->diffInDays($endDate) + 1;
    }
}
