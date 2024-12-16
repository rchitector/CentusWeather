<?php

namespace App\Livewire;

use App\Models\UserSettings;
use App\Services\OpenWeatherMapApiService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
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

    /**
     * Finding user settings if they exist and saving them to local properties
     *
     * @return void
     */
    public function mount(): void
    {
        if ($this->settings = Auth::user()->settings) {
            $this->city_name = $this->settings->city_name ?? null;
            $this->city_country = $this->settings->city_country ?? null;
            $this->city_state = $this->settings->city_state ?? null;
            $this->city_lat = $this->settings->city_lat ?? null;
            $this->city_lon = $this->settings->city_lon ?? null;
        }
    }

    /**
     * Make city settings modal opened
     *
     * @return void
     */
    public function openCityModal(): void
    {
        $this->isModalOpen = true;
        $this->dispatch('modalOpen');
    }

    /**
     * Make city settings modal closed
     * Clear the modal form data to default
     *
     * @return void
     */
    public function closeCityModal(): void
    {
        $this->isModalOpen = false;
        $this->search = '';
        $this->cities = [];
        $this->searched = false;
    }

    /**
     * Request cities data from the service
     * Mark "searched" flag as true
     *
     * @return void
     */
    public function searchCities(): void
    {
        if (!empty($this->search)) {
            $this->cities = OpenWeatherMapApiService::getCitiesGeoByName($this->search);
        } else {
            $this->cities = [];
        }
        $this->searched = true;
    }

    /**
     * Save selected in modal form city to local properties
     *
     * @param array $city - Expected structure:
     *                     - 'name' (string): City name (requires).
     *                     - 'country' (string, optional): Country name.
     *                     - 'state' (string, optional): State name.
     *                     - 'lat' (float, optional): Latitude.
     *                     - 'lon' (float, optional): Longitude.
     * @return void
     */
    public function selectCity(array $city): void
    {
        $this->city_name = $city['name'];
        $this->city_country = $city['country'] ?? null;
        $this->city_state = $city['state'] ?? null;
        $this->city_lat = $city['lat'] ?? null;
        $this->city_lon = $city['lon'] ?? null;

        $this->changed = $this->city_lat != $this->settings?->city_lat
            || $this->city_lon != $this->settings?->city_lon
            || $this->city_name != $this->settings?->city_name;

        $this->closeCityModal();
    }

    /**
     * Update user settings with a new city date.
     * If settings do not exist, new ones are created.
     * After saving, the "changed" flag is reset.
     * Create flash message for user settings page
     *
     * @return void
     */
    public function save(): void
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


    /**
     * Rendering main settings form template
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.settings-form');
    }
}
