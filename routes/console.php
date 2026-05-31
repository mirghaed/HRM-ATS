<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('hrm:send-interview-reminders')->everyFiveMinutes();
Schedule::command('hrm:recalculate-scores')->dailyAt('03:00');
Schedule::command('hrm:cleanup-temp-files')->daily();
