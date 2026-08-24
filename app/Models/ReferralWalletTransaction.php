<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralWalletTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'points' => 'decimal:2',
        'amount_equivalent' => 'decimal:2',
    ];

    public function wallet()
    {
        return $this->belongsTo(ReferralWallet::class, 'wallet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referral()
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }

    public function serviceChargeInvoice()
    {
        return $this->belongsTo(ServiceChargeInvoice::class, 'service_charge_invoice_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getSourceBadgeAttribute()
    {
        return match ($this->source) {
            'referral_registration' => ['label' => 'Friend Registered', 'class' => 'bg-blue-500/20 text-blue-400 border-blue-500/30', 'icon' => 'fa-user-plus'],
            'referral_profile' => ['label' => 'Profile Completed', 'class' => 'bg-purple-500/20 text-purple-400 border-purple-500/30', 'icon' => 'fa-id-badge'],
            'referral_interview' => ['label' => 'Interview Scheduled', 'class' => 'bg-amber-500/20 text-amber-400 border-amber-500/30', 'icon' => 'fa-handshake'],
            'referral_placement' => ['label' => 'Placement Success', 'class' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30', 'icon' => 'fa-award'],
            'service_charge_redemption' => ['label' => 'Service Charge Discount', 'class' => 'bg-rose-500/20 text-rose-400 border-rose-500/30', 'icon' => 'fa-tag'],
            'admin_adjustment' => ['label' => 'Admin Adjustment', 'class' => 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30', 'icon' => 'fa-sliders-h'],
            'welcome_bonus' => ['label' => 'Welcome Bonus', 'class' => 'bg-teal-500/20 text-teal-400 border-teal-500/30', 'icon' => 'fa-gift'],
            default => ['label' => ucwords(str_replace('_', ' ', $this->source)), 'class' => 'bg-slate-500/20 text-slate-400 border-slate-500/30', 'icon' => 'fa-coins'],
        };
    }
}
