<?php

namespace Database\Factories;

use App\Models\Buyer;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $role = [
            Buyer::class => User::BUYER,
            Seller::class => User::SELLER,
        ];

        $user = [
            'username' => $this->faker->unique()->word(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'deposit' => $this->faker->randomNumber(3),
            'userable_type' => $this->faker->randomElement([Buyer::class, Seller::class]),
        ];

        $user['userable_id'] = $user['userable_type']::factory()->create()->id;
        $user['role'] = $role[$user['userable_type']];

        return $user;
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
