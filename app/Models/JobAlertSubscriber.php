<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobAlertSubscriber extends Model
{
    protected $fillable = [
        'email',
        'is_active',
        'unsubscribe_token',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function generateUniqueToken(): string
    {
        do {
            $token = Str::random(64);
        } while (static::where('unsubscribe_token', $token)->exists());

        return $token;
    }
}
