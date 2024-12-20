<?php

namespace App\Livewire;

use App\Models\UserSettings;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Livewire\Component;

class WeatherForm extends Component
{
    public Collection $cities;
    public UserSettings $settings;

    public function mount(): void
    {
        $this->cities = auth()->user()->cities()->with('weather')->get();
        $this->settings = auth()->user()->settings;
    }

    public function render(): Application|Factory|View|\Illuminate\View\View
    {
        return view('livewire.weather-form');
    }
}
