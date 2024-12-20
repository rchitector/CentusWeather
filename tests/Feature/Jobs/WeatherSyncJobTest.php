<?php

use App\Jobs\WeatherSyncJob;
use App\Services\OpenWeatherMapApiService;

test('weather sync job dispatched', function () {
    Queue::fake();
    dispatch(new WeatherSyncJob());
    Queue::assertPushed(WeatherSyncJob::class);
});

test('OpenWeatherMapApiService::checkUpdates method', function () {
    $openWeatherMapApiService = Mockery::mock(OpenWeatherMapApiService::class);
    $openWeatherMapApiService->shouldReceive('checkUpdates')->once();
    $openWeatherMapApiService->checkUpdates();
});


