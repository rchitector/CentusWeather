<?php

namespace App\Services;

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
                ->withQueryParameters(['appid' => config('openweathermap.appid')])
                ->withQueryParameters($queryParameters)
                ->get(config('openweathermap.geo'));
        } catch (ConnectionException $e) {
            Log::error($e->getMessage());
        }
        return null;
    }

    public static function getCitiesGeoByName(string $city)
    {
        $response = self::makeGeoRequest(['q' => $city, 'limit' => 100]);
        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }

}
