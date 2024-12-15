<?php

namespace App\Livewire;

use App\Models\UserSettings;
use App\Services\OpenWeatherMapApiService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SettingsForm extends Component
{
    public $settings;
    public $city_name = null;
    public $city_country = null;
    public $city_state = null;
    public $city_lat = null;
    public $city_lon = null;
    public $search = '';
    public $searched = false;
    public $changed = false;
    public $cities = [];
    public $isModalOpen = false;

    protected $rules = [
        'city_name' => 'required|string|max:255',
        'city_country' => 'nullable|string|max:255',
        'city_state' => 'nullable|string|max:255',
        'city_lat' => 'nullable|numeric',
        'city_lon' => 'nullable|numeric',
    ];

    public function mount()
    {
        if ($this->settings = Auth::user()->settings) {
            $this->city_name = $this->settings->city_name ?? null;
            $this->city_country = $this->settings->city_country ?? null;
            $this->city_state = $this->settings->city_state ?? null;
            $this->city_lat = $this->settings->city_lat ?? null;
            $this->city_lon = $this->settings->city_lon ?? null;
        }
    }

    public function openCityModal()
    {
        $this->isModalOpen = true;
        $this->dispatch('modalOpen');
    }

    public function closeCityModal()
    {
        $this->isModalOpen = false;
        $this->search = '';
        $this->cities = [];
        $this->searched = false;
    }

    public function searchCities()
    {
        if (!empty($this->search)) {
            $this->cities = OpenWeatherMapApiService::getCitiesGeoByName($this->search);
        } else {
            $this->cities = [];
        }
        $this->searched = true;
    }

    public function selectCity($city)
    {
        $this->city_name = $city['name'];
        $this->city_country = $city['country'] ?? null;
        $this->city_state = $city['state'] ?? null;
        $this->city_lat = $city['lat'] ?? null;
        $this->city_lon = $city['lon'] ?? null;

        $this->changed = (
            $this->city_lat != $this->settings?->city_lat
            || $this->city_lon != $this->settings?->city_lon
            || $this->city_name != $this->settings?->city_name
        );

        $this->closeCityModal();
    }

    public function save()
    {
        $this->validate();

        $this->settings = Auth::user()->settings ?: new UserSettings();
        if (!$this->settings->exists) {
            $this->settings->user_id = Auth::user()->id;
        }
        $this->settings->city_name = $this->city_name;
        $this->settings->city_country = $this->city_country;
        $this->settings->city_state = $this->city_state;
        $this->settings->city_lat = $this->city_lat;
        $this->settings->city_lon = $this->city_lon;
        $this->settings->save();

        $this->changed = false;

        session()->flash('message', __('Settings were saved.'));
    }

    public function render()
    {
        return view('livewire.settings-form');
    }
}
