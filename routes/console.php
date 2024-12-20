<?php

use App\Jobs\WeatherSyncJob;

Schedule::job(new WeatherSyncJob())
    ->name('weather_sync')
    ->description('Job: Check weather updates')
    ->hourly();
