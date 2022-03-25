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

class ProductTest extends TestCase
{
    /**
     * Test that products can be gotten after creating
     *
     * @return void
     */
    public function test_products_can_be_gotten()
    {
        $count = 5;
        $seller = Seller::factory()->create();
        $seller_user = User::factory()->hasSeller()->create([
            'userable_id' => $seller->id,
            'userable_type' => Seller::class,
            'role' => User::SELLER,
            'password' => bcrypt('password')
        ]);
        $seller->user()->create($seller_user->toArray());

        $products = Product::factory($count)->create(['sellerId' => $seller_user->userable_id]);

        Sanctum::actingAs($seller_user);
        $response = $this->getJson(route('products.all'));

        $response->assertOk();
        $response->assertJson([
            'success' => true
        ]);
        $this->assertEquals($count, count($products));
    }
}
