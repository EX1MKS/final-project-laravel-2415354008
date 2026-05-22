<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiRoutingAndFilteringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test GET /api/subscriptions
     */
    public function test_get_all_subscriptions(): void
    {
        $response = $this->getJson('/api/subscriptions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'customer_id',
                        'service_id',
                        'start_date',
                        'end_date',
                        'status',
                        'customer',
                        'service',
                    ]
                ]
            ]);
    }

    /**
     * Test GET /api/subscriptions/ with trailing slash
     */
    public function test_get_all_subscriptions_with_trailing_slash(): void
    {
        $response = $this->getJson('/api/subscriptions/');

        $response->assertStatus(200);
    }

    /**
     * Test GET /api/subscriptions?status=active
     */
    public function test_get_subscriptions_by_status(): void
    {
        $response = $this->getJson('/api/subscriptions?status=active');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $data = $response->json('data');
        $this->assertNotEmpty($data);
        foreach ($data as $subscription) {
            $this->assertEquals('active', $subscription['status']);
        }
    }

    /**
     * Test GET /api/subscriptions?customer_id=1
     */
    public function test_get_subscriptions_by_customer_id(): void
    {
        $response = $this->getJson('/api/subscriptions?customer_id=1');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        foreach ($data as $subscription) {
            $this->assertEquals(1, $subscription['customer_id']);
        }
    }

    /**
     * Test GET /api/subscriptions?service_id=1
     */
    public function test_get_subscriptions_by_service_id(): void
    {
        $response = $this->getJson('/api/subscriptions?service_id=1');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        foreach ($data as $subscription) {
            $this->assertEquals(1, $subscription['service_id']);
        }
    }

    /**
     * Test GET /api/subscriptions/active returns 404
     */
    public function test_get_subscription_by_invalid_string_id_returns_404(): void
    {
        $response = $this->getJson('/api/subscriptions/active');

        $response->assertStatus(404)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Subscription not found');
    }

    /**
     * Test GET /api/customers/active returns 404
     */
    public function test_get_customer_by_invalid_string_id_returns_404(): void
    {
        $response = $this->getJson('/api/customers/active');

        $response->assertStatus(404)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Customer not found');
    }

    /**
     * Test GET /api/services/active returns 404
     */
    public function test_get_service_by_invalid_string_id_returns_404(): void
    {
        $response = $this->getJson('/api/services/active');

        $response->assertStatus(404)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Service not found');
    }
}
