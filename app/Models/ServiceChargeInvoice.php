<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceChargeInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'points_redeemed' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function redemptions()
    {
        return $this->hasMany(ReferralRedemption::class, 'service_charge_invoice_id');
    }

    public function getGrossAmountAttribute(): float
    {
        return (float) ($this->amount + $this->late_fee);
    }

    public function getNetAmountAttribute(): float
    {
        $gross = $this->gross_amount;
        $discount = (float) ($this->discount_amount ?? 0);
        return max(0, $gross - $discount);
    }
}
