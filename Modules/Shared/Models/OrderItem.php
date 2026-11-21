<?php

namespace Modules\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'website_id',
        'vendor_id',
        'vendor_price',
        'client_price',
        'anchor_text',
        'target_url',
        'anchor_deadline',
        'status',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'website_id' => 'integer',
        'vendor_id' => 'integer',
        'vendor_price' => 'decimal:2',
        'client_price' => 'decimal:2',
        'anchor_deadline' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Parent Order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Website for this guest post.
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * Vendor/Seller providing this website.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'vendor_id');
    }

    /**
     * Payments linked to this specific guest post.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_item_id');
    }
}
