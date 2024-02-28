<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FallFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        return [
            "user_id" => function() {
                return \App\Models\User::factory()->create()->id;
            },
            "location" => $this->faker->address,
            "latitude" => $this->faker->latitude(),
            "longitude" => $this->faker->longitude(),
            "severity" => $this->faker->randomElement(["danger", "info", "ok"]),
        ];
    }
}
