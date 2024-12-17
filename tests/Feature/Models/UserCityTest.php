<?php

namespace Tests\Models;

use App\Models\User;
use App\Models\UserCity;
use Illuminate\Support\Str;

function getLondon(): array
{
    return  [
        "id" => (string)Str::uuid(),
        "new" => true,
        "hidden" => false,
        "name" => 'London',
        "lat" => 51.5073219,
        "lon" => -0.1276474,
        "country" => "GB",
        "state" => "England",
    ];
}

it('returns full location with city, state, and country', function () {
    // Arrange
    $user = User::factory()->create();
    $cityData = getLondon();
    $userCity = UserCity::factory()->for($user)->create([
        'name' => $cityData['name'],
        'country' => $cityData['country'],
        'state' => $cityData['state'],
    ]);

    // Assert
    $fullLocation = "{$cityData['name']} ({$cityData['country']}, {$cityData['state']})";
    expect($userCity->full_location)->toBe($fullLocation);
});

it('returns full location only with city', function () {
    // Arrange
    $user = User::factory()->create();
    $cityData = getLondon();
    $userCity = UserCity::factory()->for($user)->create([
        'name' => $cityData['name'],
        'country' => null,
        'state' => null,
    ]);

    // Assert
    $fullLocation = $cityData['name'];
    expect($userCity->full_location)->toBe($fullLocation);
});

