<?php

use App\Jobs\WeatherSyncJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new WeatherSyncJob())->description('Job: Check weather updates')->hourly();

Artisan::command('weather', function () {
    dispatch(new WeatherSyncJob());
});
