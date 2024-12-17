<?php

namespace App\Livewire;

use App\Models\UserCity;
use App\Services\OpenWeatherMapApiService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class SettingsForm extends Component
{
    /**
     * Contains stored in DB user cities.
     * Needed to compare with draft cities changes
     * @var array
     */
    public array $originalCities = [];

    /**
     * Contains draft user cities
     * @var array
     */
    public array $draftCities = [];

    /**
     * Indicates Cities form changes
     * @var bool
     */
    public bool $isCitiesFormChanged = false;

    /**
     * Indicates Settings form changes
     * @var bool
     */
    public bool $isSettingsFormChanged = false;

    /**
     * Contains searched city name
     * @var string
     */
    public string $search = '';

    /**
     * Indicates the search was made at least once
     * @var bool
     */
    public bool $searched = false;

    /**
     * Contains found cities in external service
     * @var array
     */
    public array $foundCities = [];

    /**
     * Contains stored in DB user settings.
     * Needed to compare with draft settings changes
     * @var array
     */
    public array $originalSettings = [];

    /**
     * Contains draft user settings
     * @var array
     */
    public array $draftSettings = [];

    /**
     * Contains static rain information
     * @var array
     */
    public array $rainRanges = [];

    /**
     * Contains static snow information
     * @var array
     */
    public array $snowRanges = [];

    /**
     * Contains static UVI information
     * @var array
     */
    public array $uviRanges = [];

    /**
     * Initialises all static data
     * Loads user settings and cities
     * @return void
     */
    public function mount(): void
    {
        $this->snowRanges = config('weather.snow_ranges');
        $this->rainRanges = config('weather.rain_ranges');
        $this->uviRanges = config('weather.uvi_ranges');

        $this->resetCities();
        $this->resetSettings();
    }

    /**
     * Resets cities properties to default values
     * @return void
     */
    public function resetCities(): void
    {
        $this->originalCities = Auth::user()->cities?->toArray() ?? [];
        $this->draftCities = $this->originalCities;
    }

    /**
     * Resets settings properties to default values
     * @return void
     */
    public function resetSettings(): void
    {
        $this->originalSettings = Auth::user()->settings?->toArray() ?? [
            'rain_enabled' => false,
            'snow_enabled' => false,
            'uvi_enabled' => false,
            'rain_value' => 0,
            'snow_value' => 0,
            'uvi_value' => 0,
        ];
        $this->draftSettings = $this->originalSettings;
    }

    /**
     * Requests cities data from the service
     * @return void
     */
    public function searchCities(): void
    {
        if (!empty($this->search)) {
            $cities = OpenWeatherMapApiService::getCitiesGeoByName($this->search);
            $this->foundCities = collect($cities)->mapWithKeys(function ($item) {
                $item['id'] = (string)Str::uuid();
                return [$item['id'] => $item];
            })->values()->toArray();
            $this->filterFoundCities();
        } else {
            $this->foundCities = [];
        }
        $this->searched = true;
    }

    /**
     * Checks if the passed city data already exists in the user cities
     * @param array $draftCities
     * @param array $city
     * @return bool
     */
    public static function isSameDraftCityExist(array $draftCities, array $city): bool
    {
        return array_filter($draftCities, function ($item) use ($city) {
                return ($item['name'] ?? null) == ($city['name'] ?? null)
                    && ($item['country'] ?? null) == ($city['country'] ?? null)
                    && ($item['state'] ?? null) == ($city['state'] ?? null);
            }) !== [];
    }

    /**
     * Actualises the found cities visibility
     * Only needed for display to user
     * @return void
     */
    public function filterFoundCities(): void
    {
        foreach ($this->foundCities as &$item) {
            $item['hidden'] = self::isSameDraftCityExist($this->draftCities, $item);
        }
    }

    /**
     * Adds selected city to draft user cities
     * Random UUID is placed to "id" field just for the arrays loops logic unification
     * @param string $cityId - UUID
     * @return void
     */
    public function addCity(string $cityId): void
    {
        $city = array_filter($this->foundCities, function ($item) use ($cityId) {
            return $item['id'] == $cityId;
        });
        $city = reset($city); // get single array item
        if (!$city) {
            return;
        }
        $cityValidator = Validator::make($city, [
            'name' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('user_cities')->where(function ($query) use ($city) {
                    $query->where('country', $city['country'] ?? null)
                        ->where('state', $city['state'] ?? null)
                        ->where('lat', $city['lat'] ?? null)
                        ->where('lon', $city['lon'] ?? null);
                }),
            ],
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric|between:-90,90',
            'lon' => 'nullable|numeric|between:-180,180',
        ]);
        if ($cityValidator->fails()) {
            return;
        }
        $userCity = new UserCity($cityValidator->validated());
        $newCity = $userCity->toArray();
        $newCity['id'] = (string)Str::uuid();
        $newCity['new'] = true;
        $newCity['created_at'] = Carbon::now();
        $this->draftCities[] = $newCity;
        usort($this->draftCities, function ($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });

        $this->filterFoundCities();

        $this->checkCitiesChanges();
    }

    /**
     * Hides city from the draftCities array and shows it back in foundCities array (if city is new)
     * @param $cityId
     * @return void
     */
    public function deleteCity($cityId): void
    {
        $this->draftCities = array_filter($this->draftCities, function ($item) use ($cityId) {
            return $item['id'] != $cityId;
        });
        $this->filterFoundCities();
        $this->checkCitiesChanges();
    }

    /**
     * Compares original cities with draft cities by fixed fields array
     * @return void
     */
    public function checkCitiesChanges(): void
    {
        $this->isCitiesFormChanged = false;

        if (count($this->originalCities) !== count($this->draftCities)) {
            $this->isCitiesFormChanged = true;
            return;
        }

        $isSame = function ($original, $draft) {
            $fields = ['name', 'country', 'state', 'lat', 'lon'];
            foreach ($fields as $field) {
                if (($original[$field] ?? null) !== ($draft[$field] ?? null)) {
                    return false;
                }
            }
            return true;
        };
        foreach ($this->originalCities as $index => $originalCity) {
            if (!isset($this->draftCities[$index]) || !$isSame($originalCity, $this->draftCities[$index])) {
                $this->isCitiesFormChanged = true;
                return;
            }
        }
    }

    /**
     * Compares original settings with draft settings by fixed fields array
     * @return void
     */
    public function checkSettingsChanges(): void
    {
        $this->isSettingsFormChanged = false;
        $fields = ['rain_enabled', 'snow_enabled', 'uvi_enabled', 'rain_value', 'snow_value', 'uvi_value'];
        foreach ($fields as $field) {
            if (($this->originalSettings[$field] ?? null) != ($this->draftSettings[$field] ?? null)) {
                $this->isSettingsFormChanged = true;
                break;
            }
        }
    }

    /**
     * Undoes all not saved cities form changes
     * @return void
     */
    public function resetCitiesForm(): void
    {
        $this->draftCities = $this->originalCities;

        $this->filterFoundCities();
        $this->checkCitiesChanges();

        session()->flash('cities_message', __('Cities reverted successfully.'));
    }

    /**
     * Undoes all not saved settings form changes
     * @return void
     */
    public function resetSettingsForm(): void
    {
        $this->draftSettings = $this->originalSettings;

        $this->checkSettingsChanges();

        session()->flash('settings_message', __('Settings reverted successfully.'));
    }

    /**
     * Synchronizes all cities form changes with DB
     * Resets cities form with fresh DB data
     * @return void
     */
    public function saveCitiesForm(): void
    {
        $existed = array_filter($this->draftCities, function ($item) {
            return !isset($item['new']) || $item['new'] !== true;
        });
        $new = array_filter($this->draftCities, function ($item) {
            return isset($item['new']) && $item['new'] === true;
        });

        $existedIds = array_column($existed, 'id');
        Auth::user()->cities()->whereNotIn('id', $existedIds)->delete();

        Auth::user()->cities()->createMany($new);

        $this->resetCities();

        $this->isCitiesFormChanged = false;
        session()->flash('cities_message', __('Cities were saved.'));
    }

    /**
     * Synchronizes all settings form changes with DB
     * Resets settings form with fresh DB data
     * @return void
     */
    public function saveSettingsForm(): void
    {
        Auth::user()->settings()->updateOrCreate([], $this->draftSettings);

        $this->resetSettings();

        $this->checkSettingsChanges();

        session()->flash('settings_message', __('Settings were saved.'));
    }

    /**
     * Fires settings changes indicators recalculation
     * @param $field
     * @return void
     */
    public function updated($field): void
    {
        $settingsFields = ['draftSettings.rain_enabled', 'draftSettings.snow_enabled', 'draftSettings.uvi_enabled', 'draftSettings.rain_value', 'draftSettings.snow_value', 'draftSettings.uvi_value'];
        if (in_array($field, $settingsFields, true)) {
            $this->checkSettingsChanges();
        }
    }

    /**
     * Generates
     * @return mixed|null
     */
    public function getRainDescription(): mixed
    {
        foreach ($this->rainRanges as $range) {
            if ($this->draftSettings['rain_value'] >= $range['min'] && $this->draftSettings['rain_value'] <= $range['max']) {
                return $range['description'];
            }
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public function getSnowDescription(): mixed
    {
        foreach ($this->snowRanges as $range) {
            if ($this->draftSettings['snow_value'] >= $range['min'] && $this->draftSettings['snow_value'] <= $range['max']) {
                return $range['description'];
            }
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public function getUviDescription(): mixed
    {
        foreach ($this->uviRanges as $range) {
            if ($this->draftSettings['uvi_value'] >= $range['min'] && $this->draftSettings['uvi_value'] <= $range['max']) {
                return $range['description'];
            }
        }
        return null;
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
