<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Caregiver>
 */
class CaregiverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->name,
            "photo" => $this->faker->imageUrl(),
            "date_of_birth" => $this->faker->date(),
            "phone" => $this->faker->phoneNumber,
            "email" => $this->faker->email,
            "email_verified_at" => $this->faker->dateTime(),
            "password" => $this->faker->password,
            "country" => $this->faker->country,
            "address" => $this->faker->address,
            "rating" => $this->faker->numberBetween(0, 5),
            "gender" => $this->faker->randomElement(["male", "female"]),
        ];
    }
}
