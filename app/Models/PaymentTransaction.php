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
}
