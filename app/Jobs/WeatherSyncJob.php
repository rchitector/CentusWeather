<?php

namespace App\Jobs;

use App\Services\MailingService;
use App\Services\OpenWeatherMapApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class WeatherSyncJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        OpenWeatherMapApiService::checkUpdates();
        OpenWeatherMapApiService::checkWeatherLimits(new MailingService);
    }
}
