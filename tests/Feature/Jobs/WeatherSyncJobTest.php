<?php

use App\Services\OpenWeatherMapApiService;

test('OpenWeatherMapApiService::checkUpdates method', function () {
    $openWeatherMapApiService = Mockery::mock(OpenWeatherMapApiService::class);
    $openWeatherMapApiService->shouldReceive('checkUpdates')->once();
    $openWeatherMapApiService->checkUpdates();
});
