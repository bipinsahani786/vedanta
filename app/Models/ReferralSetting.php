<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ReferralSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function get(string $key, $default = null)
    {
        return Cache::remember('referral_setting_' . $key, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, $value, ?string $description = null)
    {
        Cache::forget('referral_setting_' . $key);
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'description' => $description]
        );
    }
}
