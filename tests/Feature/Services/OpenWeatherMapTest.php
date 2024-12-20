<?php

use App\Models\HourlyWeather;
use App\Models\User;
use App\Models\UserCity;
use App\Models\UserSettings;
use App\Services\MailingService;
use App\Services\OpenWeatherMapApiService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

it('has correct OpenWeatherMap config file', function () {
    $configName = 'openweathermap';

    $configPath = config_path($configName . '.php');
    expect($configPath)->toBeReadableFile();

    $configData = config($configName);
    expect($configData)->toBeArray()
        ->and($configData)->not->toBeEmpty()
        ->and($configData)->toHaveKeys(['geo', 'weather', 'appid', 'units'])
        ->and(config($configName . '.geo'))->toBeUrl()
        ->and(config($configName . '.weather'))->toBeUrl()
        ->and(config($configName . '.appid'))->not->toBeEmpty()
        ->and(config($configName . '.units'))->not->toBeEmpty();
});

it('can get valid Cities data', function () {
    $cityName = 'Dnipro'; // Valid city name
    $cities = OpenWeatherMapApiService::getCitiesGeoByName($cityName);
    expect($cities)->toBeArray()
        ->and($cities)->not->toBeEmpty();
});

it('can get invalid Cities empty array', function () {
    $cityName = 'Invalid City Name';
    $cities = OpenWeatherMapApiService::getCitiesGeoByName($cityName);
    expect($cities)->toBeArray()
        ->and($cities)->toBeEmpty();
});

it('can detect weather limit exceeding', function () {
    $user = User::factory()->create()->fresh();
    $settings = UserSettings::factory()->for($user)->create([
        'rain_enabled' => true,
        'snow_enabled' => true,
        'uvi_enabled' => true,
        'rain_value' => 0.1,
        'snow_value' => 0.1,
        'uvi_value' => 0.1,
        'start_notification_at' => Carbon::now(),
    ]);
    UserCity::factory()->for($user)->create([
        "name" => 'London',
        "lat" => 51.5073219,
        "lon" => -0.1276474,
        "country" => "GB",
        "state" => "England",
    ]);
    $data = [
        "hourly" => [
            ["dt" => Carbon::now()->addHours(1)->timestamp, "uvi" => 0, "rain" => ["1h" => 0], "snow" => ["1h" => 0]],
            ["dt" => Carbon::now()->addHours(2)->timestamp, "uvi" => 0.22, "rain" => ["1h" => 0.2], "snow" => ["1h" => 0.22]],
            ["dt" => Carbon::now()->addHours(3)->timestamp, "uvi" => 0.32, "rain" => ["1h" => 0.3], "snow" => ["1h" => 0.32]],
        ]
    ];
    Http::fake(['*' => Http::response($data)]);
    OpenWeatherMapApiService::checkUpdates();
    expect(HourlyWeather::all()->count())->toBe(count($data['hourly']));

    $settings->start_notification_at = Carbon::now()->addHours(5);
    $settings->save();
    $settings = OpenWeatherMapApiService::getSettingsForNotification();
    expect($settings->count())->toBe(0);
});
