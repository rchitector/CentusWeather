<?php

use App\Models\User;
use App\Models\UserSettings;

test('to array', function () {
    $userSettings = UserSettings::factory()->create()->fresh();

    expect(array_keys($userSettings->toArray()))->toBe([
        'id',
        'user_id',
        'created_at',
        'updated_at',
        'rain_enabled',
        'snow_enabled',
        'uvi_enabled',
        'rain_value',
        'snow_value',
        'uvi_value',
    ]);
});

test('casts', function () {
    $userSettings = UserSettings::factory()->create()->fresh();

    expect($userSettings->rain_enabled)->toBeBool()
        ->and($userSettings->snow_enabled)->toBeBool()
        ->and($userSettings->uvi_enabled)->toBeBool()
        ->and($userSettings->rain_value)->toBeFloat()
        ->and($userSettings->snow_value)->toBeFloat()
        ->and($userSettings->uvi_value)->toBeFloat();
});

test('user relation', function () {
    $userSettings = UserSettings::factory()->create()->fresh();

    expect($userSettings->user)->not()->toBeNull()
        ->and($userSettings->user)->toBeInstanceOf(User::class);
});
