<?php

namespace App\DTO;

use App\Models\UserCity;
use DateTime;
use Illuminate\Support\Carbon;

class HourlyWeatherDTO
{
    public Carbon $dt;
    public float $temp;
    public float $feels_like;
    public int $pressure;
    public int $humidity;
    public float $dew_point;
    public float $uvi;
    public int $clouds;
    public int $visibility;
    public float $wind_speed;
    public int $wind_deg;
    public float $wind_gust;
    public float $pop;
    public float $rain_1h;
    public float $snow_1h;

    public static function fromResponseArray(array $data, UserCity $userCity): array
    {
        return [
            'dt' => $data['dt'] ?? null,
            'user_city_id' => $userCity->id,
            'temp' => $data['temp'] ?? 0.0,
            'feels_like' => $data['feels_like'] ?? 0.0,
            'pressure' => $data['pressure'] ?? 0,
            'humidity' => $data['humidity'] ?? 0,
            'dew_point' => $data['dew_point'] ?? 0.0,
            'uvi' => $data['uvi'] ?? 0.0,
            'clouds' => $data['clouds'] ?? 0,
            'visibility' => $data['visibility'] ?? 0,
            'wind_speed' => $data['wind_speed'] ?? 0.0,
            'wind_deg' => $data['wind_deg'] ?? 0,
            'wind_gust' => $data['wind_gust'] ?? 0.0,
            'pop' => $data['pop'] ?? 0.0,
            'rain_1h' => $data['rain']['1h'] ?? 0.0,
            'snow_1h' => $data['snow']['1h'] ?? 0.0,
        ];
    }

}
