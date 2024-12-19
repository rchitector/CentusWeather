<?php

namespace Database\Factories;

use App\Models\UserCity;
use App\Models\HourlyWeather;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HourlyWeather>
 */
class HourlyWeatherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = HourlyWeather::class;

    public function definition(): array
    {
        return [
            'user_city_id' => UserCity::factory(),
            'dt' => $this->faker->dateTime,
            'temp' => $this->faker->randomFloat(2, -30, 50),
            'feels_like' => $this->faker->randomFloat(2, -30, 50),
            'pressure' => $this->faker->numberBetween(950, 1050),
            'humidity' => $this->faker->numberBetween(0, 100),
            'dew_point' => $this->faker->randomFloat(2, -30, 30),
            'uvi' => $this->faker->randomFloat(2, 0, 11),
            'clouds' => $this->faker->numberBetween(0, 100),
            'visibility' => $this->faker->numberBetween(0, 10000),
            'wind_speed' => $this->faker->randomFloat(2, 0, 100),
            'wind_deg' => $this->faker->numberBetween(0, 360),
            'wind_gust' => $this->faker->randomFloat(2, 0, 100),
            'pop' => $this->faker->randomFloat(2, 0, 1),
            'rain_1h' => $this->faker->randomFloat(2, 0, 50),
            'snow_1h' => $this->faker->randomFloat(2, 0, 50),
        ];
    }
}
