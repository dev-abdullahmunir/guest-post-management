<?php

namespace Modules\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends Model
{
    use SoftDeletes;

    protected $table = 'sellers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'country',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Websites inventory provided by this vendor.
     */
    public function websites()
    {
        return $this->hasMany(Website::class, 'vendor_id');
    }

    /**
     * Disbursements / Payouts paid to this vendor.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'vendor_id');
    }
}
