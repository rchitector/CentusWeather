<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class UserCity extends Model
{
    use HasFactory;

    protected $table = 'user_cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'country',
        'state',
        'lat',
        'lon',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'lon' => 'float',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'full_location'
    ];

    /**
     * Get the city's full location as "City Name (State, Country)".
     * Returns only the city name if state or country is unavailable.
     * @return string|null
     */
    public function getFullLocationAttribute(): string|null
    {
        $location = collect([$this->country ?? null, $this->state ?? null,])->filter()->join(', ');
        if (!empty($location)) {
            return $this->name . " ($location)";
        }
        return $this->name;
    }

    /**
     * Get User relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function weather(): HasMany
    {
        return $this->hasMany(HourlyWeather::class);
    }

    public function scopeWithEnabledSettings($query)
    {
        return $query->whereHas('user.settings', function ($query) {
            $query->where('rain_enabled', true)
                ->orWhere('snow_enabled', true)
                ->orWhere('uvi_enabled', true);
        });
    }

    public function scopeWithUpcomingWeatherData($query, $settings)
    {
        return $query->with(['weather' => function ($query) use ($settings) {
            $query->where('dt', '>', Carbon::now()->timestamp)
                ->where('dt', '<=', Carbon::now()->addHours(config('weather.number_of_visible_next_hours'))->timestamp)
                ->where(function ($query) use ($settings) {
                    if ($settings->rain_value > 0) {
                        $query->orWhere('rain_1h', '>', $settings->rain_value);
                    }
                    if ($settings->snow_value > 0) {
                        $query->orWhere('snow_1h', '>=', $settings->snow_value);
                    }
                    if ($settings->uvi_value > 0) {
                        $query->orWhere('uvi', '>=', $settings->uvi_value);
                    }
                })
                ->orderBy('dt')
                ->get()
//                ->filter(function ($weather, $key) use (&$previousWeather) { // filter out the hourly weather data that is not consecutive
//                    if ($key === 0) {
//                        $previousWeather = $weather;
//                        return true;
//                    }
//                    $result = Carbon::createFromTimestamp($previousWeather->dt)->diffInHours(Carbon::createFromTimestamp($weather->dt));
//                    if ($result === 1.0) {
//                        $previousWeather = $weather;
//                    }
//                    return $result === 1.0;
//                })
            ;
        }]);
    }
}
