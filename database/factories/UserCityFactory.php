<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserCity;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCityFactory extends Factory
{
    protected $model = UserCity::class;

    /**
     * Define the UserSettings model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->city,
            'country' => $this->faker->country,
            'state' => $this->faker->state,
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
        ];
    }
}
