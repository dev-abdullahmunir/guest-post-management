<?php

namespace Modules\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'order_item_id',
        'client_id',
        'vendor_id',
        'payment_type',
        'amount',
        'currency',
        'payment_method',
        'transaction_id',
        'payment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'order_item_id' => 'integer',
        'client_id' => 'integer',
        'vendor_id' => 'integer',
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship to Client.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Relationship to Vendor (Seller).
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'vendor_id');
    }

    /**
     * Relationship to specific guest post Order Item.
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    /**
     * Scope for Received Payments (Inflow).
     */
    public function scopeReceived($query)
    {
        return $query->where('payment_type', 'received');
    }

    /**
     * Scope for Paid Payments (Outflow).
     */
    public function scopePaid($query)
    {
        return $query->where('payment_type', 'paid');
    }
}
