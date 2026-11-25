<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobAlertDispatch extends Model
{
    protected $fillable = [
        'job_post_id',
        'sent_count',
        'dispatched_at',
    ];

    protected $casts = [
        'dispatched_at' => 'datetime',
    ];
}
