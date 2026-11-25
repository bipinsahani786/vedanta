<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'link_text',
        'link_url',
        'style',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only active announcements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check whether link_url uses a safe relative or http(s) URL.
     */
    public function hasSafeLink(): bool
    {
        if (empty($this->link_url)) {
            return false;
        }

        $url = trim($this->link_url);

        return str_starts_with($url, '/')
            || str_starts_with($url, 'https://')
            || str_starts_with($url, 'http://');
    }
}
