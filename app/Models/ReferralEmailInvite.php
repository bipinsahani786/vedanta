<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralEmailInvite extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'sent_at' => 'datetime',
        'registered_at' => 'datetime',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'sent' => ['label' => 'Sent', 'class' => 'bg-blue-500/20 text-blue-400 border-blue-500/30', 'icon' => 'fa-paper-plane'],
            'opened' => ['label' => 'Opened', 'class' => 'bg-purple-500/20 text-purple-400 border-purple-500/30', 'icon' => 'fa-envelope-open'],
            'registered' => ['label' => 'Registered', 'class' => 'bg-amber-500/20 text-amber-400 border-amber-500/30', 'icon' => 'fa-user-check'],
            'converted' => ['label' => 'Converted', 'class' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30', 'icon' => 'fa-trophy'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-slate-500/20 text-slate-400 border-slate-500/30', 'icon' => 'fa-info-circle'],
        };
    }
}
