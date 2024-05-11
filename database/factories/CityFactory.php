<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\City;
use App\Models\Country;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city,
            'country_id' => function () {
                return Country::factory()->create()->id;
            },
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'postal_code' => $this->faker->regexify('[0-9]{5}'),
            'archived' => $this->faker->boolean(20)
        ];
    }
}
