<?php

use App\Models\User;
use App\Models\UserSettings;
use Livewire\Livewire;

it('mounts and initializes settings from the user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $settings = UserSettings::factory()->create(['user_id' => $user->id]);

    Livewire::test(App\Livewire\SettingsForm::class)
        ->assertSet('city_name', $settings->city_name)
        ->assertSet('city_country', $settings->city_country)
        ->assertSet('city_state', $settings->city_state)
        ->assertSet('city_lat', $settings->city_lat)
        ->assertSet('city_lon', $settings->city_lon);
});

it('opens and closes the city modal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(App\Livewire\SettingsForm::class)
        ->call('openCityModal')
        ->assertSet('isModalOpen', true)
        ->call('closeCityModal')
        ->assertSet('isModalOpen', false)
        ->assertSet('search', '')
        ->assertSet('cities', [])
        ->assertSet('searched', false);
});


it('searches cities and updates the cities array', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $searchTerm = 'London';
    Livewire::test(App\Livewire\SettingsForm::class)
        ->set('search', $searchTerm)
        ->call('searchCities')
        ->assertSet('search', $searchTerm)
        ->assertSet('cities', function ($cities) {
            return is_array($cities) && !empty($cities);
        })
        ->assertSet('searched', true);
});

it('selects a city and updates the properties', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $name = 'Dnipro';
    $country = 'UA';
    $state = 'Dnipropetrovsk Oblast';
    $lat = 48.4680221;
    $lon = 35.0417711;
    $city = ['name' => $name, 'country' => $country, 'state' => $state, 'lat' => $lat, 'lon' => $lon];

    Livewire::test(App\Livewire\SettingsForm::class)
        ->call('selectCity', $city)
        ->assertSet('city_name', $name)
        ->assertSet('city_country', $country)
        ->assertSet('city_state', $state)
        ->assertSet('city_lat', $lat)
        ->assertSet('city_lon', $lon)
        ->assertSet('changed', true);
});

it('saves the settings correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $newSettings = [
        'city_name' => 'Paris',
        'city_country' => 'FR',
        'city_state' => 'Île-de-France',
        'city_lat' => 48.8566,
        'city_lon' => 2.3522,
    ];

    $response = Livewire::test(App\Livewire\SettingsForm::class)
        ->set('city_name', $newSettings['city_name'])
        ->set('city_country', $newSettings['city_country'])
        ->set('city_state', $newSettings['city_state'])
        ->set('city_lat', $newSettings['city_lat'])
        ->set('city_lon', $newSettings['city_lon'])
        ->call('save')
        ->assertSet('changed', false);

    expect($response)->assertSessionHasFlash('message', __('Settings were saved.'));
});

it('fails validation when fields are invalid', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(App\Livewire\SettingsForm::class)
        ->set('city_name', '')
        ->call('save')
        ->assertHasErrors(['city_name' => 'required']);
});
