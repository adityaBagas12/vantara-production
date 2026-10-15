<?php

namespace App\Models;

use Database\Factories\OperationalCostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationalCost extends Model
{
    /** @use HasFactory<OperationalCostFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
