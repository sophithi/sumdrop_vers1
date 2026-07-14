<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_sales_report(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Order::create([
            'user_id' => $admin->id,
            'subtotal' => 12.50,
            'tax' => 0.00,
            'total' => 12.50,
            'status' => 'completed',
            'payment_method' => 'cash',
            'currency' => 'usd',
        ]);

        $response = $this->actingAs($admin)->get('/reports');

        $response->assertStatus(200);
        $response->assertSee('Sales Report');
        $response->assertSee('12.50');
    }
}
