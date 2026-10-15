<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    /**
     * Tampilkan daftar seluruh pesanan masuk dengan filter & pencarian.
     */
    public function index(Request $request): View
    {
        $query = Order::with('orderItems');

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian (Kode, Nama, Telepon)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'LIKE', "%{$search}%")
                    ->orWhere('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                    ->orWhere('event_address', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->orderBy('id', 'desc')->paginate(12);

        // Counter status per tab
        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'dp_received' => Order::where('status', 'dp_received')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Tampilkan detail pesanan spesifik.
     */
    public function show(int $id): View
    {
        $order = Order::with(['orderItems.package', 'operationalCosts'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function storeOperationalCost(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $order->operationalCosts()->create([
            'name' => $validated['name'],
            'amount' => (int) $validated['amount'],
        ]);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Biaya operasional berhasil ditambahkan.');
    }

    public function updateOperationalCost(Request $request, int $id, int $costId): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $cost = $order->operationalCosts()->findOrFail($costId);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $cost->update([
            'name' => $validated['name'],
            'amount' => (int) $validated['amount'],
        ]);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Biaya operasional berhasil diperbarui.');
    }

    public function destroyOperationalCost(int $id, int $costId): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->operationalCosts()->findOrFail($costId)->delete();

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Biaya operasional berhasil dihapus.');
    }

    /**
     * Perbarui status pesanan, pencatatan DP, total harga, dan catatan admin.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,dp_received,confirmed,completed,cancelled',
            'down_payment' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:2000',
        ], [
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.in' => 'Status pesanan tidak valid.',
        ]);

        $order->status = $validated['status'];

        if (isset($validated['down_payment'])) {
            $order->down_payment = (int) $validated['down_payment'];
        }

        // Jika DP diisi > 0 dan status masih pending, otomatis ubah status ke dp_received
        if ($order->down_payment > 0 && $order->status === 'pending') {
            $order->status = 'dp_received';
        }

        if (isset($validated['total_price'])) {
            $order->total_price = (int) $validated['total_price'];
        }

        if ($request->has('admin_notes')) {
            $order->admin_notes = $validated['admin_notes'];
        }

        $order->save();

        if (isset($validated['total_price'])) {
            $this->syncItemSubtotals($order);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Status & data transaksi pesanan '.$order->order_code.' berhasil diperbarui.');
    }

    /**
     * Sesuaikan subtotal item agar jumlah rincian mengikuti total kesepakatan.
     */
    private function syncItemSubtotals(Order $order): void
    {
        /** @var Collection<int, \App\Models\OrderItem> $items */
        $items = $order->orderItems()->get();
        $originalTotal = (int) $items->sum('subtotal');
        $remainingTotal = (int) $order->total_price;
        $lastIndex = $items->keys()->last();

        foreach ($items as $index => $item) {
            $subtotal = $index === $lastIndex
                ? max(0, $remainingTotal)
                : ($originalTotal > 0
                    ? (int) round($item->subtotal / $originalTotal * $order->total_price)
                    : 0);

            $item->update(['subtotal' => $subtotal]);
            $remainingTotal -= $subtotal;
        }
    }

    /**
     * Hapus pesanan (Soft Delete).
     */
    public function destroy(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $code = $order->order_code;
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan '.$code.' telah dihapus dari daftar.');
    }
}
