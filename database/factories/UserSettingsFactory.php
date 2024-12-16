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
            'city_name' => $this->faker->city,
            'city_country' => $this->faker->country,
            'city_state' => $this->faker->state,
            'city_lat' => $this->faker->latitude,
            'city_lon' => $this->faker->longitude,
        ];
    }
}
