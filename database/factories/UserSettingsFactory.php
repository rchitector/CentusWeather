<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserSettings>
 */
class UserSettingsFactory extends Factory
{
    /**
     * Model class being used by the factory.
     */
    protected $model = UserSettings::class;

    /**
     * Define the UserSettings model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'rain_enabled' => $this->faker->boolean(),
            'snow_enabled' => $this->faker->boolean(),
            'uvi_enabled' => $this->faker->boolean(),
            'rain_value' => $this->faker->numberBetween(0, 100),
            'snow_value' => $this->faker->numberBetween(0, 100),
            'uvi_value' => $this->faker->numberBetween(0, 100),
        ];
    }
}
