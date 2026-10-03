<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'event_address',
        'event_date',
        'event_end_date',
        'notes',
        'total_price',
        'down_payment',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'event_end_date' => 'date',
            'total_price' => 'integer',
            'down_payment' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeBooked(Builder $query): Builder
    {
        return $query->whereNotIn('status', ['cancelled']);
    }

    public static function generateOrderCode(): string
    {
        $prefix = 'VAN-'.date('Ymd').'-';
        $latestOrder = static::withTrashed()
            ->where('order_code', 'LIKE', $prefix.'%')
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($latestOrder) {
            $lastSeq = (int) substr($latestOrder->order_code, -4);
            $sequence = $lastSeq + 1;
        }

        do {
            $code = $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
            $exists = static::withTrashed()->where('order_code', $code)->exists();
            if (! $exists) {
                return $code;
            }
            $sequence++;
        } while (true);
    }
}
