<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralMilestone extends Model
{
    protected $fillable = [
        'successful_referrals_required',
        'bonus_points',
        'is_active',
    ];

    protected $casts = [
        'successful_referrals_required' => 'integer',
        'bonus_points' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
