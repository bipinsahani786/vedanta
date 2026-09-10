<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralRedemption extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'points_redeemed' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(ReferralWallet::class, 'wallet_id');
    }

    public function serviceChargeInvoice()
    {
        return $this->belongsTo(ServiceChargeInvoice::class, 'service_charge_invoice_id');
    }
}
