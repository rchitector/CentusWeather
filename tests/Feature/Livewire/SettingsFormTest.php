<?php

namespace Tests\Features\Livewire;

use App\Models\User;
use App\Models\UserCity;
use App\Models\UserSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Livewire;
use App\Livewire\SettingsForm;

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

it('mounts and initializes settings from the user', function () {
    // Arrange
    $user = User::factory()
        ->has(UserSettings::factory(), 'settings')
        ->has(UserCity::factory()->count(3), 'cities')
        ->create();
    $this->actingAs($user);

    // Act
    $component = Livewire::test(SettingsForm::class);

    // Assert
    $component->assertSet('snowRanges', config('weather.snow_ranges'));
    $component->assertSet('rainRanges', config('weather.rain_ranges'));
    $component->assertSet('uviRanges', config('weather.uvi_ranges'));

    $component->assertSet('originalSettings', $user->settings->toArray());
    $component->assertSet('draftSettings', $component->get('originalSettings'));

    $component->assertSet('originalCities', $user->cities->toArray());
    $component->assertSet('draftCities', $component->get('originalCities'));
});

it('searches empty city and updates the draft cities array', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act
    $component = Livewire::test(SettingsForm::class);

    // Act: searches London city
    $component->set('search', '');
    $component->call('searchCities');
    $component->assertSet('foundCities', []);
});

it('searches cities and updates the draft cities array', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $cityData = getLondon();
    Http::fake([
        config('openweathermap.geo') . '*' => Http::response([$cityData], 200),
    ]);

    // Act
    $component = Livewire::test(SettingsForm::class);

    // Act: searches London city
    $component->set('search', $cityData['name']);
    $component->call('searchCities');

    // Assert: checks if something found
    $component->assertSet('search', $cityData['name']);
    $component->assertSet('foundCities', function ($foundCities) {
        return is_array($foundCities) && !empty($foundCities);
    });
    $component->assertSet('searched', true);

    // Assert: checks if founded city not presents in draft cities array
    expect(SettingsForm::isSameDraftCityExist($component->get('draftCities'), $cityData))->toBeFalse();

    // Act: adds first founded city to draft cities array
    $cityId = $component->get('foundCities')[0]['id'];
    $component->call('addCity', $cityId);

    // Assert: checks if founded city presents in draft cities array
    expect(SettingsForm::isSameDraftCityExist($component->get('draftCities'), $cityData))->toBeTrue();
});

it('not found city ID in foundCities', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $cityData = getLondon();
    $cityData2 = getLondon();

    // Act
    $component = Livewire::test(SettingsForm::class);

    // Act: set empty draft cities array
    $component->set('draftCities', []);

    // Act: add found city
    $component->set('foundCities', [$cityData]);

    // Assert: check draftCities not changed
    $component->call('addCity', $cityData2['id']);
    expect($component->get('draftCities'))->toBe([]);
});

it('can delete a city from draft cities', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $cityData = getLondon();

    // Act: append city
    $component = Livewire::test(SettingsForm::class);
    $component->set('draftCities', [$cityData]);

    // Assert: checks if city exists
    expect(SettingsForm::isSameDraftCityExist($component->get('draftCities'), $cityData))->toBeTrue();

    // Act: deletes city
    $component->call('deleteCity', $cityData['id']);

    // Assert: checks if city deleted
    expect(SettingsForm::isSameDraftCityExist($component->get('draftCities'), $cityData))->toBeFalse();
});

it('can reset cities form to original state', function () {
    // Arrange
    $user = User::factory()
        ->create();
    $this->actingAs($user);
    $cityData = getLondon();

    // Act: append city
    $component = Livewire::test(SettingsForm::class);
    $component->set('draftCities', [$cityData]);

    // Act: reset cities form
    $component->call('resetCitiesForm');

    // Assert: check if draft cities reverted to original
    $component->assertSet('draftCities', $component->get('originalCities'));
});

it('can save cities form and sync with DB', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $cityData = getLondon();

    // Act
    $component = Livewire::test(SettingsForm::class);

    // Act: add draft city
    $component->set('foundCities', [$cityData]);
    $component->call('addCity', $cityData['id']);

    // Act: save cities form
    $component->call('saveCitiesForm');

    $exists = UserCity::query()->where([
        'user_id' => $user->id,
        'name' => $cityData['name'],
        'country' => $cityData['country'],
        'state' => $cityData['state'],
        'lat' => $cityData['lat'],
        'lon' => $cityData['lon'],
    ])->exists();

    // Act: check new city saved in DB
    expect($exists)->toBeTrue();

    // Act: check indicator status cleared
    expect($component->get('isCitiesFormChanged'))->toBeFalse();
});

it('can check if there are any settings changes', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act
    $component = Livewire::test(SettingsForm::class);

    // Act: update a setting
    $component->set('draftSettings.rain_enabled', true);

    // Assert: check if form change is detected
    expect($component->get('isSettingsFormChanged'))->toBeTrue();
});

it('can save settings form and sync with DB', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    $component = Livewire::test(SettingsForm::class);

    $component->set('draftSettings.rain_enabled', true);
    $component->set('draftSettings.snow_enabled', true);
    $component->set('draftSettings.uvi_enabled', true);
    $component->set('draftSettings.rain_value', 5);
    $component->set('draftSettings.snow_value', 10);
    $component->set('draftSettings.uvi_value', 7);

    // Act: save settings form
    $component->call('saveSettingsForm');

    // Assert: check message
    expect($component)->assertSessionHasFlash('settings_message', __('Settings were saved.'));

    // Assert: check if settings are saved in the DB
    $this->assertDatabaseHas('user_settings', [
        'user_id' => $user->id,
        'rain_enabled' => true,
        'snow_enabled' => true,
        'uvi_enabled' => true,
        'rain_value' => 5,
        'snow_value' => 10,
        'uvi_value' => 7,
    ]);
});

it('can reset cities', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act
    $component = Livewire::test(SettingsForm::class);

    // Act: reset cities
    $component->call('resetCities');

    // Assert: check if cities were reset
    expect($component->get('draftCities'))->toEqual($component->get('originalCities'));
});

it('can reset settings', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act
    $component = Livewire::test(SettingsForm::class);

    // Act: reset settings
    $component->call('resetSettings');

    // Assert: check if settings were reset
    expect($component->get('draftSettings'))->toEqual($component->get('originalSettings'));
});

it('can get valid descriptions', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $rainRange = config('weather.rain_ranges')[5];
    $snowRange = config('weather.snow_ranges')[5];
    $uviRange = config('weather.uvi_ranges')[5];

    // Act: sets settings values
    $component = Livewire::test(SettingsForm::class);
    $component->set('draftSettings.rain_value', $rainRange['min']);
    $component->set('draftSettings.snow_value', $snowRange['min']);
    $component->set('draftSettings.uvi_value', $uviRange['min']);

    // Assert: checks descriptions
    expect($component->instance()->getRainDescription())->toEqual($rainRange['description'])
        ->and($component->instance()->getSnowDescription())->toEqual($snowRange['description'])
        ->and($component->instance()->getUviDescription())->toEqual($uviRange['description']);
});

it('can check cities changes', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $cityData = getLondon();

    // Act: sets settings values
    $component = Livewire::test(SettingsForm::class);

    // Assert: no changes
    expect($component->get('isCitiesFormChanged'))->toBeFalse();

    $component->set('draftCities', [$cityData]);

    // Assert: calculate and check changes
    $component->call('checkCitiesChanges');
    expect($component->get('isCitiesFormChanged'))->toBeTrue();
});

it('can check settings changes', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $cityData = getLondon();

    // Act
    $component = Livewire::test(SettingsForm::class);

    // Assert: no changes
    expect($component->get('isSettingsFormChanged'))->toBeFalse();

    // Assert: calculate and check changes
    $component->set('draftSettings.rain_enabled', true);
    $component->call('checkSettingsChanges');
    expect($component->get('isSettingsFormChanged'))->toBeTrue();
});
