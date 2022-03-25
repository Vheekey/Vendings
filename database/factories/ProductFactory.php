<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $seller = Seller::factory()->create();
        User::factory()->create(['userable_id' => $seller->id, 'userable_type' => Seller::class]);

        return [
            'sellerId' => $seller->id,
            'cost' => $this->faker->randomElement([5, 10]),
            'productName' => $this->faker->unique()->word,
            'amountAvailable' => $this->faker->randomNumber(),
        ];
    }
}
