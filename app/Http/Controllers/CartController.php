<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja.
     */
    public function index(Request $request): View
    {
        if ($request->filled('event_date')) {
            session()->put('event_date', $request->input('event_date'));
        } elseif ($request->filled('date')) {
            session()->put('event_date', $request->input('date'));
        }

        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('pages.cart', compact('cart', 'total'));
    }

    /**
     * Tambahkan paket ke keranjang belanja.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'quantity' => 'nullable|integer|min:1',
            'event_date' => 'nullable|date|after_or_equal:today',
        ]);

        if ($request->filled('event_date')) {
            session()->put('event_date', $request->input('event_date'));
        }

        $package = Package::where('is_active', true)->findOrFail($request->package_id);
        $quantity = (int) ($request->quantity ?? 1);

        $cart = session()->get('cart', []);

        if (isset($cart[$package->id])) {
            $cart[$package->id]['quantity'] += $quantity;
        } else {
            $cart[$package->id] = [
                'package_id' => $package->id,
                'name' => $package->name,
                'slug' => $package->slug,
                'category' => $package->category,
                'price' => (int) $package->price,
                'image_path' => $package->image_path,
                'quantity' => $quantity,
                'items' => $package->items ?? [],
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Paket "'.$package->name.'" berhasil ditambahkan ke keranjang!');
    }

    /**
     * Perbarui kuantitas paket dalam keranjang.
     */
    public function update(Request $request, int $packageId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$packageId])) {
            $cart[$packageId]['quantity'] = (int) $request->quantity;
            session()->put('cart', $cart);

            return redirect()->route('cart.index')
                ->with('success', 'Jumlah paket berhasil diperbarui.');
        }

        return redirect()->route('cart.index')
            ->with('error', 'Paket tidak ditemukan di keranjang.');
    }

    /**
     * Hapus satu paket dari keranjang.
     */
    public function destroy(int $packageId): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$packageId])) {
            $packageName = $cart[$packageId]['name'];
            unset($cart[$packageId]);
            session()->put('cart', $cart);

            return redirect()->route('cart.index')
                ->with('success', 'Paket "'.$packageName.'" telah dihapus dari keranjang.');
        }

        return redirect()->route('cart.index');
    }

    /**
     * Kosongkan seluruh keranjang.
     */
    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return redirect()->route('packages.catalog')
            ->with('success', 'Keranjang telah dikosongkan.');
    }
}
