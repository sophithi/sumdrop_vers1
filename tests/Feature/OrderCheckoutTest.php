<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_checkout_with_json_items(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        $product = Product::create([
            'category_id' => 1,
            'name' => 'Americano',
            'sku' => 'AM-001',
            'price' => 3.50,
            'price_usd' => 3.50,
            'price_khr' => 14350,
            'status' => true,
        ]);

        $response = $this->actingAs($user)->post('/orders', [
            'currency' => 'usd',
            'items' => json_encode([
                ['product_id' => $product->id, 'quantity' => 2],
            ]),
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2]);
    }
}
