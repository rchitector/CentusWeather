<?php

use App\Models\User;
use App\Models\UserCity;
use App\Models\UserSettings;
use Illuminate\Support\Carbon;

test('to array', function () {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'name',
        'email',
        'email_verified_at',
        'current_team_id',
        'profile_photo_path',
        'created_at',
        'updated_at',
        'two_factor_confirmed_at',
        'profile_photo_url',
    ]);
});

test('dates casts', function () {
    $user = User::factory()->create([
        'two_factor_confirmed_at' => now(),
    ])->fresh();

    expect($user->created_at)->toBeInstanceOf(Carbon::class)
        ->and($user->updated_at)->toBeInstanceOf(Carbon::class)
        ->and($user->email_verified_at)->toBeInstanceOf(Carbon::class)
        ->and($user->two_factor_confirmed_at)->toBeInstanceOf(Carbon::class);
});

test('password hash', function () {
    $user = User::factory()->create()->fresh();

    expect(Hash::check('password', $user->password))->toBeTrue();
});


test('user cities relation', function () {
    $user = User::factory()->create()->fresh();

    $city1 = UserCity::factory()->for($user)->create(['created_at' => now()->subDays(2)]);
    UserCity::factory()->for($user)->create(['created_at' => now()->subDay()]);
    $city3 = UserCity::factory()->for($user)->create();

    $cities = $user->cities;

    expect($cities)->toHaveCount(3)
        ->and($cities->first()->id)->toBe($city3->id)
        ->and($cities->last()->id)->toBe($city1->id);
});

test('user settings relation', function () {
    $user = User::factory()->create()->fresh();
    UserSettings::factory()->for($user)->create();

    $settings = $user->settings;

    expect($settings)->not()->toBeNull()
        ->and($settings->user_id)->toBe($user->id);
});
