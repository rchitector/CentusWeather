<?php

use App\DTO\HourlyWeatherDTO;
use App\Models\HourlyWeather;
use App\Models\UserCity;

test('to array', function () {
    $weatherHourly = HourlyWeather::factory()->create()->fresh();
    expect(array_keys($weatherHourly->toArray()))->toBe([
        'id',
        'user_city_id',
        'dt',
        'temp',
        'feels_like',
        'pressure',
        'humidity',
        'dew_point',
        'uvi',
        'clouds',
        'visibility',
        'wind_speed',
        'wind_deg',
        'wind_gust',
        'pop',
        'rain_1h',
        'snow_1h',
        'created_at',
        'updated_at',
    ]);
});

test('casts', function () {
    $weatherHourly = HourlyWeather::factory()->create()->fresh();

    expect($weatherHourly->dt)->toBeInstanceOf(DateTime::class)
        ->and($weatherHourly->temp)->toBeFloat()
        ->and($weatherHourly->feels_like)->toBeFloat()
        ->and($weatherHourly->pressure)->toBeInt()
        ->and($weatherHourly->humidity)->toBeInt()
        ->and($weatherHourly->dew_point)->toBeFloat()
        ->and($weatherHourly->uvi)->toBeFloat()
        ->and($weatherHourly->clouds)->toBeInt()
        ->and($weatherHourly->visibility)->toBeInt()
        ->and($weatherHourly->wind_speed)->toBeFloat()
        ->and($weatherHourly->wind_deg)->toBeInt()
        ->and($weatherHourly->wind_gust)->toBeFloat()
        ->and($weatherHourly->pop)->toBeFloat()
        ->and($weatherHourly->rain_1h)->toBeFloat()
        ->and($weatherHourly->snow_1h)->toBeFloat();
});

it('can create a HourlyWeather model instance', function () {
    $weatherHourly = HourlyWeather::factory()->create()->fresh();

    expect($weatherHourly->user_city)
        ->toBeInstanceOf(UserCity::class)
        ->and($weatherHourly)->toBeInstanceOf(HourlyWeather::class)
        ->and($weatherHourly->user_city_id)->not->toBeNull()
        ->and($weatherHourly->dt)->not->toBeNull()
        ->and($weatherHourly->temp)->not->toBeNull()
        ->and($weatherHourly->feels_like)->not->toBeNull()
        ->and($weatherHourly->pressure)->not->toBeNull()
        ->and($weatherHourly->humidity)->not->toBeNull()
        ->and($weatherHourly->dew_point)->not->toBeNull()
        ->and($weatherHourly->uvi)->not->toBeNull()
        ->and($weatherHourly->clouds)->not->toBeNull()
        ->and($weatherHourly->visibility)->not->toBeNull()
        ->and($weatherHourly->wind_speed)->not->toBeNull()
        ->and($weatherHourly->wind_deg)->not->toBeNull()
        ->and($weatherHourly->wind_gust)->not->toBeNull()
        ->and($weatherHourly->pop)->not->toBeNull()
        ->and($weatherHourly->rain_1h)->not->toBeNull()
        ->and($weatherHourly->snow_1h)->not->toBeNull();
});

it('updates existing entry for the same user_city_id and dt', function () {
    $userCity = UserCity::factory()->create()->fresh();

    $hourly = [
        "dt" => 1734609600,
        "temp" => -28.01,
        "feels_like" => 34.75,
        "pressure" => 963,
        "humidity" => 90,
        "dew_point" => -17.79,
        "uvi" => 10.93,
        "clouds" => 42,
        "visibility" => 9347,
        "wind_speed" => 94.02,
        "wind_deg" => 244,
        "wind_gust" => 13.76,
        "pop" => 0.28,
        "rain_1h" => 4.77,
        "snow_1h" => 49.47,
    ];

    $hourlyWeather = HourlyWeatherDTO::fromResponseArray($hourly, $userCity);
    HourlyWeather::saveWeather($hourlyWeather, $userCity);

    $hourly['temp'] = 123.45;
    $hourlyWeather = HourlyWeatherDTO::fromResponseArray($hourly, $userCity);
    HourlyWeather::saveWeather($hourlyWeather, $userCity);

    expect(HourlyWeather::count())->toBe(1)
        ->and(HourlyWeather::first()->temp)->toBe(123.45);
});

