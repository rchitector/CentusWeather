<?php

namespace App\Services;

use App\Models\HourlyWeather;
use App\Models\UserCity;
use App\Models\UserSettings;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenWeatherMapApiService
{

    private static function makeGeoRequest(array $queryParameters = []): PromiseInterface|Response|null
    {
        try {
            return Http::asJson()
                ->withQueryParameters([
                    'appid' => config('openweathermap.appid')
                ])
                ->withQueryParameters($queryParameters)
                ->get(config('openweathermap.geo'));
        } catch (ConnectionException $e) {
            Log::error($e->getMessage());
        }
        return null;
    }

    public static function getCitiesGeoByName(string $city): array|null
    {
        $response = self::makeGeoRequest(['q' => $city, 'limit' => 100]);
        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }

    private static function makeWeatherRequest(array $queryParameters = []): PromiseInterface|Response|null
    {
        try {
            return Http::asJson()
                ->withQueryParameters([
                    'appid' => config('openweathermap.appid'),
                    'units' => config('openweathermap.units'),
                ])
                ->withQueryParameters($queryParameters)
                ->get(config('openweathermap.weather'));
        } catch (ConnectionException $e) {
            Log::error($e->getMessage());
        }
        return null;
    }

    public static function getWeatherResponse(string $lat, string $lon, string $exclude = 'current,minutely,daily,alerts'): PromiseInterface|Response|null
    {
        return self::makeWeatherRequest(['lat' => $lat, 'lon' => $lon, 'exclude' => $exclude]);
    }

    public static function checkUpdates(): void
    {
        UserCity::query()
            ->whereHas('user.settings', function ($query) {
                $query->where('rain_enabled', true)
                    ->orWhere('snow_enabled', true)
                    ->orWhere('uvi_enabled', true);
            })
            ->get()
            ->each(function ($userCity) {
                $weatherData = self::getWeatherResponse($userCity->lat, $userCity->lon)->json();
                if (isset($weatherData['hourly'])) {
                    Log::debug($userCity->name . ': ' . $userCity->lon . ':' . $userCity->lat . ': ' . count($weatherData['hourly']));
                    foreach ($weatherData['hourly'] as $hourly) {
                        HourlyWeather::saveWeather($hourly, $userCity);
                    }
                }
            });
    }

    public static function checkWeatherLimits(MailingServiceInterface $mailingService): void
    {
        UserSettings::query()
            ->where('rain_enabled', true)
            ->orWhere('snow_enabled', true)
            ->orWhere('uvi_enabled', true)
            ->get()
            ->each(function ($settings) use ($mailingService) {
                $cities = $settings->user->cities()->withUpcomingWeatherData($settings)->get();
                if ($cities->isNotEmpty()) {
                    $mailingService->sendWeatherAlert($settings->user, $cities);
                }
            });
    }
}
