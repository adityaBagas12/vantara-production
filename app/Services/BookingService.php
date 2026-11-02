<?php

namespace App\Services;

use App\Exceptions\OrderCodeConflictException;
use App\Exceptions\StockUnavailableException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    /**
     * @param  array<int|string, array<string, mixed>>  $cart
     */
    public function reserve(array $validated, array $cart, int $totalPrice, Carbon $startDate, Carbon $endDate): Order
    {
        $packageIds = collect($cart)
            ->pluck('package_id')
            ->map(fn (mixed $id): int => (int) $id)
            ->unique()
            ->sort()
            ->values()
            ->all();

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            try {
                return DB::transaction(function () use ($validated, $cart, $totalPrice, $startDate, $endDate, $packageIds): Order {
                    // This must remain the first application query in the transaction.
                    $packages = Package::whereIn('id', $packageIds)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    foreach ($cart as $item) {
                        $package = $packages->get((int) $item['package_id']);
                        $requestedQty = (int) ($item['quantity'] ?? 1);

                        if (! $package) {
                            throw new StockUnavailableException('Paket yang dipilih sudah tidak tersedia. Silakan muat ulang katalog.');
                        }

                        for ($curr = $startDate->copy(); $curr->lte($endDate); $curr->addDay()) {
                            $remainingOnDay = max(0, (int) $package->stock - $package->getBookedStockOn($curr));
                            if ($requestedQty > $remainingOnDay) {
                                throw new StockUnavailableException($this->stockMessage($item, $curr, (int) $package->stock, $remainingOnDay, $requestedQty));
                            }
                        }
                    }

                    $rentDuration = $startDate->diffInDays($endDate) + 1;

                    $order = Order::create([
                        'order_code' => $this->nextOrderCode(),
                        'customer_name' => $validated['customer_name'],
                        'customer_phone' => $validated['customer_phone'],
                        'customer_email' => $validated['customer_email'] ?? null,
                        'event_address' => $validated['event_address'],
                        'event_date' => $startDate->format('Y-m-d'),
                        'event_end_date' => $endDate->format('Y-m-d'),
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
                            'subtotal' => $item['price'] * $item['quantity'] * $rentDuration,
                            'options_json' => isset($item['options']) ? json_encode($item['options']) : null,
                        ]);
                    }

                    return $order;
                });
            } catch (QueryException $exception) {
                if (! $this->isDuplicateKey($exception)) {
                    throw $exception;
                }

                Log::warning('Order code conflict during checkout; retrying.', ['attempt' => $attempt]);
                if ($attempt === 5) {
                    Log::error('Order code conflict exhausted checkout retries.');
                    throw new OrderCodeConflictException('Pesanan belum dapat disimpan karena sistem sedang sibuk. Silakan coba lagi.');
                }
            }
        }

        throw new OrderCodeConflictException('Pesanan belum dapat disimpan karena sistem sedang sibuk. Silakan coba lagi.');
    }

    private function isDuplicateKey(QueryException $exception): bool
    {
        return $exception->getCode() === '23000'
            && (int) ($exception->errorInfo[1] ?? 0) === 1062;
    }

    protected function nextOrderCode(): string
    {
        return Order::generateOrderCode();
    }

    private function stockMessage(array $item, Carbon $date, int $stock, int $remaining, int $requested): string
    {
        $formattedDay = $date->translatedFormat('d M Y');
        if ($remaining === 0) {
            return "Maaf, paket '{$item['name']}' pada tanggal {$formattedDay} sudah terisi penuh (kapasitas {$stock} unit telah terpakai). Silakan tentukan tanggal alternatif.";
        }

        return "Maaf, paket '{$item['name']}' pada tanggal {$formattedDay} hanya tersisa {$remaining} unit (Anda meminta {$requested} unit). Silakan sesuaikan jumlah unit atau pilih tanggal lain.";
    }
}
