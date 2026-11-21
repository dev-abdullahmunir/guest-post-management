<?php

namespace Modules\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'client_id',
        'order_date',
        'deadline',
        'total_client_price',
        'total_vendor_cost',
        'total_profit',
        'status',
        'notes',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'order_date' => 'date',
        'deadline' => 'date',
        'total_client_price' => 'decimal:2',
        'total_vendor_cost' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Automatically compute profit before saving.
     */
    protected static function booted()
    {
        static::saving(function ($order) {
            $clientPrice = (float)($order->total_client_price ?? 0);
            $vendorCost = (float)($order->total_vendor_cost ?? 0);
            $order->total_profit = $clientPrice - $vendorCost;

            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
            }
        });
    }

    /**
     * Client who placed this order.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Order items (individual guest posts).
     */
    public function items(): HasMany
    {
        return $this->hasMany(\Modules\Shared\Models\OrderItem::class, 'order_id');
    }

    /**
     * Recalculate totals from items.
     */
    public function recalculateTotals(): void
    {
        $this->total_client_price = (float)$this->items()->sum('client_price');
        $this->total_vendor_cost = (float)$this->items()->sum('vendor_price');
        $this->total_profit = $this->total_client_price - $this->total_vendor_cost;
        $this->save();
    }
}
