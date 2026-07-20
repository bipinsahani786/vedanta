<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
Schedule::command('invoices:calculate-late-fees')->daily();
Schedule::command('reminders:abandoned-registration')->daily();
Schedule::command('reminders:interview')->daily();
Schedule::command('reminders:lead-follow-ups')->dailyAt('09:00');
