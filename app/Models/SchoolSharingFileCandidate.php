<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolSharingFileCandidate extends Model
{
    protected $guarded = [];

    public function sharingFile(): BelongsTo
    {
        return $this->belongsTo(SchoolSharingFile::class, 'sharing_file_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SchoolSharingCategory::class, 'category_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_user_id');
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
