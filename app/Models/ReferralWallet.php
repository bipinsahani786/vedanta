<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralWallet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'available_points' => 'decimal:2',
        'pending_points' => 'decimal:2',
        'lifetime_points' => 'decimal:2',
        'is_locked' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(ReferralWalletTransaction::class, 'wallet_id')->latest();
    }

    public function redemptions()
    {
        return $this->hasMany(ReferralRedemption::class, 'wallet_id')->latest();
    }
}
