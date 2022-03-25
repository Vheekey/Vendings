<?php

namespace Tests\Feature;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BuyerTest extends TestCase
{
   /**
    * Test that user can deposit amount
    *
    */
    public function test_deposit()
    {
        $deposit_amount = 50;
        $payload = [
            'amount' => $deposit_amount
        ];

        $buyer = Buyer::factory()->create();
        $user = User::factory()->hasBuyer()->create([
            'userable_id' => $buyer->id,
            'userable_type' => Buyer::class,
            'role' => User::BUYER,
            'password' => bcrypt('password')
        ]);
        $buyer->user()->create($user->toArray());

        $user_post_amount = $user->deposit + $deposit_amount;

        Sanctum::actingAs($user, []);
        $response = $this->postJson(route('buyers.deposit'), $payload);

        $response->assertOk();
        $this->assertEquals($user_post_amount, $user->deposit);
        $response->assertJson([
            'success' => true
        ]);
    }

    /**
     * Test User can buy Product
     *
     */
    public function test_buy()
    {
        $buyer = Buyer::factory()->create();
        $user = User::factory()->hasBuyer()->create([
            'userable_id' => $buyer->id,
            'userable_type' => Buyer::class,
            'role' => User::BUYER,
            'password' => bcrypt('password')
        ]);
        $buyer->user()->create($user->toArray());


        $seller = Seller::factory()->create();
        $seller_user = User::factory()->hasSeller()->create([
            'userable_id' => $seller->id,
            'userable_type' => Seller::class,
            'role' => User::SELLER,
            'deposit' => 100,
            'password' => bcrypt('password')
        ]);
        $seller->user()->create($user->toArray());
        $product = Product::factory()->create(['sellerId' => $seller_user->userable_id, 'amountAvailable' => 10]);

        $payload = [
            'quantity' => 1,
            'productId' => $product->id
        ];

        $balance = $user->deposit - ($product->cost * $payload['quantity']);

        Sanctum::actingAs($user);
        $response = $this->postJson(route('buyers.buy', ['product' => $product->id]), $payload);

        $response->assertOk();
        $this->assertEquals($balance, $user->deposit);
        $response->assertJson([
            'success' => true
        ]);
        $response->assertJsonStructure([
            'data' => [
                'total_spent',
                'change',
            ]
        ]);
    }
}
