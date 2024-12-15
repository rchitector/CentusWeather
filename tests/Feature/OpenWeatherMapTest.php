<?php

use App\Services\OpenWeatherMapApiService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

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
