<?php

namespace Tests\Models;

use App\Models\User;
use App\Models\UserCity;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;

test('to array', function () {
    $userCity = UserCity::factory()->create()->fresh();
    expect(array_keys($userCity->toArray()))->toBe([
        'id',
        'user_id',
        'name',
        'country',
        'state',
        'lat',
        'lon',
        'created_at',
        'updated_at',
        'full_location',
    ]);
});

test('dates casts', function () {
    $userCity = UserCity::factory()->create()->fresh();

    expect($userCity->created_at)->toBeInstanceOf(Carbon::class)
        ->and($userCity->updated_at)->toBeInstanceOf(Carbon::class);
});

test('full location', function () {
    // Arrange
    $user = User::factory()->create()->fresh();
    $userCity = UserCity::factory()->for($user)->create();

    // Assert
    $fullLocation = "{$userCity->name} ({$userCity->country}, {$userCity->state})";
    expect($userCity->full_location)->toBe($fullLocation);
});

test('name as full location', function () {
    // Arrange
    $user = User::factory()->create()->fresh();
    $userCity = UserCity::factory()->for($user)->create([
        'country' => null,
        'state' => null,
    ]);

    // Assert
    $fullLocation = $userCity->name;
    expect($userCity->full_location)->toBe($fullLocation);
});

test('name with country as full location', function () {
    // Arrange
    $user = User::factory()->create()->fresh();
    $userCity = UserCity::factory()->for($user)->create([
        'state' => null,
    ]);

    // Assert
    $fullLocation = "{$userCity->name} ({$userCity->country})";
    expect($userCity->full_location)->toBe($fullLocation);
});

test('name with state as full location', function () {
    // Arrange
    $user = User::factory()->create()->fresh();
    $userCity = UserCity::factory()->for($user)->create([
        'country' => null,
    ]);

    // Assert
    $fullLocation = "{$userCity->name} ({$userCity->state})";
    expect($userCity->full_location)->toBe($fullLocation);
});

it('should not create user city without a name', function () {
    $user = User::factory()->create()->fresh();

    try {
        UserCity::factory()->for($user)->create(['name' => null]);
    } catch (QueryException $exception) {
        expect($exception->getCode())->toBe('23000');
    }

    $userCity = UserCity::factory()->for($user)->create([
        'name' => 'Test City'
    ]);
    expect($userCity->name)->toBe('Test City');
});

test('user relation', function () {
    $user = User::factory()->create();
    $userCity = UserCity::factory()->for($user)->create();

    expect($userCity->user)->not()->toBeNull()
        ->and($userCity->user)->toBeInstanceOf(User::class)
        ->and($userCity->user->id)->toBe($user->id);
});

test('attribute casting', function () {
    $userCity = UserCity::factory()->create([
        'lat' => '10.0',
        'lon' => '20.0',
    ]);

    expect($userCity->lat)->toBeFloat()
        ->and($userCity->lon)->toBeFloat();
});

it('should not allow duplicate user cities with the same attributes', function () {
    $user = User::factory()->create();

    $userCity = [
        'name' => 'Test City',
        'country' => 'Test Country',
        'state' => 'Test State',
        'lat' => 10.0,
        'lon' => 20.0,
    ];

    UserCity::factory()->for($user)->create($userCity);

    expect(fn () => UserCity::factory()->for($user)->create($userCity))->toThrow(QueryException::class);
});
