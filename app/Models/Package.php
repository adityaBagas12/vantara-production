<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\PackageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Package extends Model
{
    /** @use HasFactory<PackageFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'items',
        'price',
        'stock',
        'is_featured',
        'is_active',
        'image_path',
        'gallery_images',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'integer',
            'stock' => 'integer',
            'gallery_images' => 'array',
        ];
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Hitung jumlah unit barang yang telah dibooking pada tanggal tertentu.
     */
    public function getBookedStockOn(string|Carbon $date): int
    {
        $dateStr = $date instanceof Carbon ? $date->format('Y-m-d') : Carbon::parse($date)->format('Y-m-d');

        return (int) $this->orderItems()
            ->whereHas('order', function (Builder $query) use ($dateStr) {
                $query->whereNotIn('status', ['cancelled'])
                    ->whereDate('event_date', '<=', $dateStr)
                    ->whereDate(DB::raw('COALESCE(event_end_date, event_date)'), '>=', $dateStr);
            })
            ->sum('quantity');
    }

    /**
     * Hitung sisa unit barang yang masih tersedia pada tanggal tertentu.
     */
    public function getAvailableStockOn(string|Carbon $date): int
    {
        $booked = $this->getBookedStockOn($date);
        $totalStock = $this->stock ?? 1;

        return max(0, $totalStock - $booked);
    }

    /**
     * Hitung unit barang minimum yang tersedia selama rentang tanggal.
     */
    public function getAvailableStock(string|Carbon $startDate, string|Carbon|null $endDate = null): int
    {
        $start = $startDate instanceof Carbon ? $startDate->copy()->startOfDay() : Carbon::parse($startDate)->startOfDay();
        $end = $endDate ? ($endDate instanceof Carbon ? $endDate->copy()->startOfDay() : Carbon::parse($endDate)->startOfDay()) : $start->copy();

        $minAvailable = $this->stock ?? 1;

        for ($curr = $start->copy(); $curr->lte($end); $curr->addDay()) {
            $availOnDay = $this->getAvailableStockOn($curr);
            if ($availOnDay < $minAvailable) {
                $minAvailable = $availOnDay;
            }
        }

        return max(0, $minAvailable);
    }
}
