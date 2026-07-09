<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_product_detail_page_loads(): void
    {
        $this->seed(\Database\Seeders\TestDataSeeder::class);

        $response = $this->get('/productos/chocolate-ucayali-70');

        $response->assertStatus(200);
    }
}
