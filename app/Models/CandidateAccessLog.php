<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateAccessLog extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'granted_permissions' => 'array',
        ];
    }

    public function accessRequest(): BelongsTo
    {
        return $this->belongsTo(CandidateAccessRequest::class, 'access_request_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(User::class, 'school_user_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_user_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
