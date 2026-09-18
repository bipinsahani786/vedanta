<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'gateway_response' => 'array',
    ];

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function getFormattedDescriptionAttribute(): string
    {
        if ($this->type === 'service_charge' || str_starts_with($this->transaction_id ?? '', 'SC_') || str_starts_with($this->transaction_id ?? '', 'MANUAL_SC_')) {
            return 'Service Charge (Placement)';
        }

        if (str_starts_with($this->transaction_id ?? '', 'UPGRADE_')) {
            return 'Registration Fee - Part 2 (Upgrade to Premium)';
        }

        if (str_starts_with($this->transaction_id ?? '', 'RENEW_PREMIUM_')) {
            return 'Plan Renewal (Premium Plan)';
        }

        if (str_starts_with($this->transaction_id ?? '', 'RENEW_BASIC_')) {
            return 'Plan Renewal (Standard Plan)';
        }

        if ((float)$this->amount >= 1000) {
            return 'Registration Fee (Full / Premium Plan)';
        }

        if ((float)$this->amount == 500) {
            return 'Registration Fee - Part 1';
        }

        return 'Registration Fee';
    }
}
