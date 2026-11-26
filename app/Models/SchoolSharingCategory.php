<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolSharingCategory extends Model
{
    protected $guarded = [];

    public function sharingFile(): BelongsTo
    {
        return $this->belongsTo(SchoolSharingFile::class, 'sharing_file_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(SchoolSharingFileCandidate::class, 'category_id')->latest('id');
    }
}
