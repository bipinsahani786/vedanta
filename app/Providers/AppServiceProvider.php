<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || request()->server('HTTP_X_FORWARDED_PROTO') === 'https' || str_starts_with(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\View::composer(['layouts.app', 'partials.whatsapp-channel-card'], function ($view) {
            $activeAnnouncement = null;
            $whatsappChannelUrl = null;
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('announcements')) {
                    $activeAnnouncement = \App\Models\Announcement::active()
                        ->latest('updated_at')
                        ->first();
                }
                $whatsappChannelUrl = \App\Models\SiteSetting::getActiveWhatsAppChannelUrl();
            } catch (\Throwable $e) {
                $activeAnnouncement = null;
                $whatsappChannelUrl = null;
            }

            $view->with([
                'activeAnnouncement' => $activeAnnouncement,
                'whatsappChannelUrl' => $whatsappChannelUrl,
            ]);
        });
    }
}
