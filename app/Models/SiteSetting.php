<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function getValue(string $key, $default = null)
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return $default;
            }

            $record = static::where('key', $key)->first();

            return $record ? $record->value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function setValue(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Validate and return the active WhatsApp Channel URL if enabled and properly formatted.
     */
    public static function getActiveWhatsAppChannelUrl(): ?string
    {
        $enabled = static::getValue('whatsapp_channel_enabled', '0');
        if ($enabled !== '1') {
            return null;
        }

        $url = trim((string) static::getValue('whatsapp_channel_url', ''));

        return static::isValidWhatsAppChannelUrl($url) ? $url : null;
    }

    public static function isValidWhatsAppChannelUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $trimmed = trim($url);

        return (bool) preg_match('/^https:\/\/(www\.)?whatsapp\.com\/channel\/[A-Za-z0-9_-]+(\/.*|\?.*)?$/i', $trimmed);
    }
}
