<?php

namespace App\Models;

use App\DTO\HourlyWeatherDTO;
use Carbon\Carbon;
use Database\Factories\HourlyWeatherFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class HourlyWeather extends Model
{
    /** @use HasFactory<HourlyWeatherFactory> */
    use HasFactory;

    protected $fillable = [
        'user_city_id',
        'dt',
        'temp',
        'feels_like',
        'pressure',
        'humidity',
        'dew_point',
        'uvi',
        'clouds',
        'visibility',
        'wind_speed',
        'wind_deg',
        'wind_gust',
        'pop',
        'rain_1h',
        'snow_1h',
    ];

    protected $casts = [
        'dt' => 'int', // <<< DO NOT MAKE THIS FIELD A DATETIME INSTANCE TO PREVENT DUPLICATE ENTRIES
        'temp' => 'float',
        'feels_like' => 'float',
        'pressure' => 'integer',
        'humidity' => 'integer',
        'dew_point' => 'float',
        'uvi' => 'float',
        'clouds' => 'integer',
        'visibility' => 'integer',
        'wind_speed' => 'float',
        'wind_deg' => 'integer',
        'wind_gust' => 'float',
        'pop' => 'float',
        'rain_1h' => 'float',
        'snow_1h' => 'float',
    ];

    public function getDateAttribute(): Carbon
    {
        return Carbon::createFromTimestamp($this->dt);
    }

    public function user_city(): BelongsTo
    {
        return $this->belongsTo(UserCity::class);
    }

    public static function saveWeather(array $hourly, UserCity $userCity): HourlyWeather
    {
        $weatherDTO = HourlyWeatherDTO::fromResponseArray($hourly, $userCity);
        return HourlyWeather::updateOrCreate(
            Arr::only($weatherDTO, ['user_city_id', 'dt']),
            Arr::except($weatherDTO, ['user_city_id', 'dt'])
        );
    }
}
